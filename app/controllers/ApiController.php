<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Audit;
use App\Core\Auth;
use App\Core\Response;
use PDO;

final class ApiController
{
    public function __construct(private PDO $pdo)
    {
    }

    /* ───── Auth ───── */

    public function login(array $input): void
    {
        $phone    = trim((string) ($input['phone'] ?? ''));
        $password = (string) ($input['password'] ?? '');

        if ($phone === '' || $password === '') {
            Response::json(['success' => false, 'message' => 'Phone and password are required'], 422);
        }

        $stmt = $this->pdo->prepare(
            'SELECT u.id, u.full_name, u.password_hash, r.name AS role_name
             FROM users u INNER JOIN roles r ON r.id = u.role_id
             WHERE u.phone = :phone AND u.is_active = 1 LIMIT 1'
        );
        $stmt->execute([':phone' => $phone]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            Response::json(['success' => false, 'message' => 'Invalid credentials'], 401);
        }

        Auth::login($user);
        Audit::log($this->pdo, (int) $user['id'], 'auth', 'login', 'users', (int) $user['id'], null, ['status' => 'logged_in'], 'User logged in');

        Response::json([
            'success' => true,
            'message' => 'Login successful',
            'data'    => ['user' => Auth::user()],
        ]);
    }

    /* ───── Dashboard ───── */

    public function dashboardStats(): void
    {
        $members  = (int) $this->pdo->query("SELECT COUNT(*) FROM members WHERE member_status='active'")->fetchColumn();
        $events   = (int) $this->pdo->query("SELECT COUNT(*) FROM `events`")->fetchColumn();
        $income   = (float) $this->pdo->query("SELECT COALESCE(SUM(fe.amount),0) FROM finance_entries fe INNER JOIN finance_categories fc ON fc.id=fe.category_id WHERE fc.category_type='income' AND DATE_FORMAT(fe.entry_date,'%Y-%m')=DATE_FORMAT(CURRENT_DATE,'%Y-%m')")->fetchColumn();
        $expenses = (float) $this->pdo->query("SELECT COALESCE(SUM(fe.amount),0) FROM finance_entries fe INNER JOIN finance_categories fc ON fc.id=fe.category_id WHERE fc.category_type='expense' AND DATE_FORMAT(fe.entry_date,'%Y-%m')=DATE_FORMAT(CURRENT_DATE,'%Y-%m')")->fetchColumn();

        $upcoming = $this->pdo->query(
            "SELECT id, title, start_datetime, category, status
             FROM `events`
             WHERE start_datetime >= NOW()
             ORDER BY start_datetime ASC
             LIMIT 5"
        )->fetchAll();

        Response::json([
            'success' => true,
            'message' => 'Dashboard stats',
            'data'    => compact('members', 'events', 'income', 'expenses', 'upcoming'),
        ]);
    }

    public function dashboardInsights(): void
    {
        $month = trim((string) ($_GET['month'] ?? date('Y-m')));
        if (preg_match('/^\d{4}-\d{2}$/', $month) !== 1) {
            Response::json(['success' => false, 'message' => 'Invalid month format. Use YYYY-MM'], 422);
        }

        $monthStart = $month . '-01';
        $monthEnd = date('Y-m-t', strtotime($monthStart));

        $eventStmt = $this->pdo->prepare(
            'SELECT e.id, e.title, e.category, e.start_datetime, e.venue, e.target_group_id, e.notes
             FROM `events` e
             WHERE e.start_datetime BETWEEN :start_at AND :end_at
             ORDER BY e.start_datetime ASC'
        );
        $eventStmt->execute([
            ':start_at' => $monthStart . ' 00:00:00',
            ':end_at' => $monthEnd . ' 23:59:59',
        ]);

        $dbEvents = array_map(function (array $event): array {
            $event['kind'] = $this->resolveEventKind((string) $event['category'], (string) ($event['notes'] ?? ''));
            $event['is_system'] = false;
            $event['is_editable'] = true;
            $event['tag'] = $this->kindTag((string) $event['kind']);
            return $event;
        }, $eventStmt->fetchAll());

        $systemEvents = $this->buildSystemChurchEvents($month);
        $allMonthEvents = array_merge($dbEvents, $systemEvents);
        usort($allMonthEvents, fn (array $a, array $b): int => strcmp((string) $a['start_datetime'], (string) $b['start_datetime']));

        $now = date('Y-m-d H:i:s');
        $upcoming = array_values(array_filter($allMonthEvents, fn (array $e): bool => (string) $e['start_datetime'] >= $now));
        $upcoming = array_slice($upcoming, 0, 5);

        $this->ensureAttendanceSnapshotsTable();

        $sundaySummaries = [];
        foreach ($this->sundaysInMonth($monthStart) as $sundayDate) {
            // Primary source: aggregate attendance snapshots from Attendance Center.
            $snapshotAttendanceStmt = $this->pdo->prepare(
                'SELECT COALESCE(SUM(total_count), 0)
                 FROM attendance_snapshots
                 WHERE service_date = :sunday'
            );
            $snapshotAttendanceStmt->execute([':sunday' => $sundayDate]);
            $attendance = (int) $snapshotAttendanceStmt->fetchColumn();

            // Backward compatibility: fallback to event-level attendance data when snapshots are missing.
            if ($attendance === 0) {
                $attendanceStmt = $this->pdo->prepare(
                    "SELECT COALESCE(SUM(CASE WHEN ea.status='present' THEN 1 ELSE 0 END), 0)
                     FROM event_attendance ea
                     INNER JOIN events e ON e.id = ea.event_id
                     WHERE DATE(e.start_datetime) = :sunday"
                );
                $attendanceStmt->execute([':sunday' => $sundayDate]);
                $attendance = (int) $attendanceStmt->fetchColumn();
            }

            $offeringStmt = $this->pdo->prepare(
                "SELECT COALESCE(SUM(fe.amount), 0)
                 FROM finance_entries fe
                 INNER JOIN finance_categories fc ON fc.id = fe.category_id
                 WHERE fc.category_type = 'income' AND DATE(fe.entry_date) = :sunday"
            );
            $offeringStmt->execute([':sunday' => $sundayDate]);
            $offering = (float) $offeringStmt->fetchColumn();

            $sundaySummaries[$sundayDate] = [
                'attendance' => $attendance,
                'offering' => $offering,
            ];
        }

        $lastSundayDate = date('Y-m-d', strtotime('-' . date('w') . ' days'));
        $lastSundaySummary = $sundaySummaries[$lastSundayDate] ?? ['attendance' => 0, 'offering' => 0.0];

        $weekStart = date('Y-m-d', strtotime('monday this week'));
        $weeklyStmt = $this->pdo->prepare(
            "SELECT COALESCE(SUM(fe.amount), 0)
             FROM finance_entries fe
             INNER JOIN finance_categories fc ON fc.id = fe.category_id
             WHERE fc.category_type = 'income' AND DATE(fe.entry_date) BETWEEN :week_start AND CURRENT_DATE"
        );
        $weeklyStmt->execute([':week_start' => $weekStart]);
        $weeklyOffering = (float) $weeklyStmt->fetchColumn();

        $monthlyStmt = $this->pdo->query(
            "SELECT COALESCE(SUM(fe.amount), 0)
             FROM finance_entries fe
             INNER JOIN finance_categories fc ON fc.id = fe.category_id
             WHERE fc.category_type = 'income' AND DATE_FORMAT(fe.entry_date, '%Y-%m') = DATE_FORMAT(CURRENT_DATE, '%Y-%m')"
        );
        $monthlyIncome = (float) $monthlyStmt->fetchColumn();

        $nextSundayDate = date('Y-m-d', strtotime('next sunday'));
        $nextSundayFocus = null;
        foreach ($allMonthEvents as $event) {
            if (str_starts_with((string) $event['start_datetime'], $nextSundayDate)) {
                $nextSundayFocus = $event;
                break;
            }
        }
        if ($nextSundayFocus === null) {
            $nextSundayFocus = [
                'title' => 'Worship Service',
                'start_datetime' => $nextSundayDate . ' 09:00:00',
                'tag' => 'Worship',
                'is_system' => true,
            ];
        }

        $specialUpcoming = null;
        foreach ($allMonthEvents as $event) {
            if (($event['kind'] ?? '') === 'special' && (string) $event['start_datetime'] >= $now) {
                $specialUpcoming = $event;
                break;
            }
        }

        Response::json([
            'success' => true,
            'message' => 'Dashboard insights',
            'data' => [
                'upcoming' => $upcoming,
                'sunday_summaries' => $sundaySummaries,
                'last_sunday' => [
                    'date' => $lastSundayDate,
                    'attendance' => (int) $lastSundaySummary['attendance'],
                    'offering' => (float) $lastSundaySummary['offering'],
                    'trend' => $weeklyOffering >= (float) $lastSundaySummary['offering'] ? 'up' : 'down',
                ],
                'highlights' => [
                    'next_sunday_focus' => $nextSundayFocus,
                    'special_upcoming' => $specialUpcoming,
                ],
                'financial_snapshot' => [
                    'last_sunday_offering' => (float) $lastSundaySummary['offering'],
                    'weekly_total' => $weeklyOffering,
                    'monthly_income' => $monthlyIncome,
                ],
            ],
        ]);
    }

    /* ───── Members ───── */

    public function listMembers(): void
    {
        $search = trim((string) ($_GET['search'] ?? ''));
        $status = trim((string) ($_GET['status'] ?? ''));
        $gender = trim((string) ($_GET['gender'] ?? ''));

        $sql = 'SELECT id, member_code, first_name, last_name, phone, email, gender,
                       member_status, join_date, ward, district, region, date_of_birth
                FROM members WHERE 1=1';
        $params = [];

        if ($search !== '') {
            $sql .= ' AND (first_name LIKE :s1 OR last_name LIKE :s2 OR phone LIKE :s3 OR member_code LIKE :s4 OR email LIKE :s5)';
            $like = '%' . $search . '%';
            $params[':s1'] = $like;
            $params[':s2'] = $like;
            $params[':s3'] = $like;
            $params[':s4'] = $like;
            $params[':s5'] = $like;
        }
        if ($status !== '' && in_array($status, ['active', 'inactive', 'transferred', 'deceased'], true)) {
            $sql .= ' AND member_status = :status';
            $params[':status'] = $status;
        }
        if ($gender !== '' && in_array($gender, ['male', 'female', 'other'], true)) {
            $sql .= ' AND gender = :gender';
            $params[':gender'] = $gender;
        }

        $sql .= ' ORDER BY id DESC LIMIT 500';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        Response::json(['success' => true, 'message' => 'Members list', 'data' => $stmt->fetchAll()]);
    }

    public function memberStats(): void
    {
        $row = $this->pdo->query(
            "SELECT
                COUNT(*) AS total,
                SUM(member_status = 'active') AS active,
                SUM(member_status = 'inactive') AS inactive,
                SUM(member_status = 'transferred') AS transferred,
                SUM(member_status = 'deceased') AS deceased,
                SUM(gender = 'male') AS male,
                SUM(gender = 'female') AS female
             FROM members"
        )->fetch();
        Response::json(['success' => true, 'message' => 'Member stats', 'data' => $row]);
    }

    public function createMember(array $input): void
    {
        $required = ['first_name', 'last_name', 'gender', 'phone'];
        foreach ($required as $field) {
            if (empty($input[$field])) {
                Response::json(['success' => false, 'message' => $field . ' is required'], 422);
            }
        }

        $user    = Auth::user();
        $actorId = $user['id'] ?? null;

        // Auto-generate member code if not provided
        $memberCode = trim((string) ($input['member_code'] ?? ''));
        if ($memberCode === '') {
            $codeStmt = $this->pdo->query(
                "SELECT CONCAT('MBR-', DATE_FORMAT(NOW(), '%Y'), '-', LPAD(COALESCE(MAX(id), 0) + 1, 4, '0')) FROM members"
            );
            $memberCode = (string) $codeStmt->fetchColumn();
        }

        $joinDate = trim((string) ($input['join_date'] ?? ''));
        if ($joinDate === '' || strtotime($joinDate) === false) {
            $joinDate = date('Y-m-d');
        }

        $stmt = $this->pdo->prepare(
            'INSERT INTO members (member_code, first_name, last_name, gender, phone, email,
                                  date_of_birth, join_date, marital_status, baptism_date,
                                  physical_address, ward, district, region, notes,
                                  member_status, created_by, updated_by)
             VALUES (:member_code, :first_name, :last_name, :gender, :phone, :email,
                     :date_of_birth, :join_date, :marital_status, :baptism_date,
                     :physical_address, :ward, :district, :region, :notes,
                     :status, :created_by, :updated_by)'
        );
        $n = fn(string $k): ?string => (isset($input[$k]) && $input[$k] !== '') ? trim((string) $input[$k]) : null;
        $stmt->execute([
            ':member_code'     => $memberCode,
            ':first_name'      => trim((string) $input['first_name']),
            ':last_name'       => trim((string) $input['last_name']),
            ':gender'          => trim((string) $input['gender']),
            ':phone'           => trim((string) $input['phone']),
            ':email'           => $n('email'),
            ':date_of_birth'   => $n('date_of_birth'),
            ':join_date'       => $joinDate,
            ':marital_status'  => $n('marital_status'),
            ':baptism_date'    => $n('baptism_date'),
            ':physical_address'=> $n('physical_address'),
            ':ward'            => $n('ward'),
            ':district'        => $n('district'),
            ':region'          => $n('region'),
            ':notes'           => $n('notes'),
            ':status'          => in_array($input['member_status'] ?? '', ['active','inactive','transferred','deceased'], true) ? $input['member_status'] : 'active',
            ':created_by'      => $actorId,
            ':updated_by'      => $actorId,
        ]);

        $id = (int) $this->pdo->lastInsertId();
        Audit::log($this->pdo, $actorId ? (int) $actorId : null, 'members', 'create', 'members', $id, null, ['member_code' => $memberCode, 'name' => $input['first_name'] . ' ' . $input['last_name']], 'Created member profile');

        Response::json(['success' => true, 'message' => 'Member created', 'data' => ['id' => $id, 'member_code' => $memberCode]], 201);
    }

    public function updateMember(int $id, array $input): void
    {
        $allowed = ['first_name','last_name','phone','email','gender','date_of_birth','join_date',
                    'member_status','physical_address','ward','district','region','marital_status',
                    'baptism_date','notes'];
        $set = [];
        $params = [':id' => $id];
        foreach ($allowed as $field) {
            if (array_key_exists($field, $input)) {
                $set[] = "`$field` = :$field";
                $params[":$field"] = ($input[$field] !== '' && $input[$field] !== null) ? $input[$field] : null;
            }
        }
        if (empty($set)) {
            Response::json(['success' => false, 'message' => 'Nothing to update'], 422);
        }
        $user = Auth::user();
        $actorId = $user['id'] ?? null;
        $set[] = 'updated_by = :updated_by';
        $params[':updated_by'] = $actorId;

        $stmt = $this->pdo->prepare('UPDATE members SET ' . implode(', ', $set) . ' WHERE id = :id');
        $stmt->execute($params);

        Audit::log($this->pdo, $actorId ? (int) $actorId : null, 'members', 'update', 'members', $id, null, $input, 'Updated member profile');
        Response::json(['success' => true, 'message' => 'Member updated']);
    }

    public function importMembers(): void
    {
        if (empty($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            Response::json(['success' => false, 'message' => 'No valid file uploaded. Please select a CSV or Excel file.'], 422);
        }

        $file = $_FILES['file'];
        $ext  = strtolower(pathinfo((string) $file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, ['csv', 'xlsx'], true)) {
            Response::json(['success' => false, 'message' => 'Only .csv and .xlsx files are supported.'], 422);
        }

        try {
            $rows = $ext === 'csv' ? $this->parseCsv((string) $file['tmp_name']) : $this->parseXlsx((string) $file['tmp_name']);
        } catch (\RuntimeException $e) {
            Response::json(['success' => false, 'message' => $e->getMessage()], 422);
        }

        if (count($rows) < 2) {
            Response::json(['success' => false, 'message' => 'File has no data rows (first row must be header).'], 422);
        }

        $header   = array_map(fn($v) => strtolower(trim((string) $v)), $rows[0]);
        $dataRows = array_slice($rows, 1);

        // Flexible column alias map
        $aliases = [
            'first_name'       => ['first_name','firstname','first name','jina la kwanza','jina'],
            'last_name'        => ['last_name','lastname','last name','surname','familia','jina la familia'],
            'gender'           => ['gender','jinsia','sex'],
            'phone'            => ['phone','simu','mobile','phone_number','phone number','nambari ya simu'],
            'email'            => ['email','barua pepe','barua_pepe'],
            'date_of_birth'    => ['date_of_birth','dob','birthdate','birth date','birth_date','tarehe ya kuzaliwa'],
            'join_date'        => ['join_date','joined','join date','date joined','tarehe ya kujiunga'],
            'member_status'    => ['member_status','status','hali'],
            'member_code'      => ['member_code','code','member code','nambari','namba'],
            'physical_address' => ['physical_address','address','makazi','anuani'],
            'ward'             => ['ward','mtaa'],
            'district'         => ['district','wilaya'],
            'region'           => ['region','mkoa'],
            'marital_status'   => ['marital_status','marital status','hali ya ndoa'],
            'baptism_date'     => ['baptism_date','baptism date','tarehe ya ubatizo'],
            'notes'            => ['notes','maelezo','note'],
        ];

        $fieldIdx = [];
        foreach ($aliases as $field => $aliasList) {
            foreach ($aliasList as $alias) {
                $pos = array_search($alias, $header, true);
                if ($pos !== false) {
                    $fieldIdx[$field] = (int) $pos;
                    break;
                }
            }
        }

        $user    = Auth::user();
        $actorId = $user['id'] ?? null;
        $inserted = 0;
        $skipped  = 0;
        $errors   = [];

        $stmt = $this->pdo->prepare(
            'INSERT INTO members (member_code, first_name, last_name, gender, phone, email,
                                  date_of_birth, join_date, member_status, physical_address, ward,
                                  district, region, marital_status, baptism_date, notes,
                                  created_by, updated_by)
             VALUES (:member_code, :first_name, :last_name, :gender, :phone, :email,
                     :date_of_birth, :join_date, :member_status, :physical_address, :ward,
                     :district, :region, :marital_status, :baptism_date, :notes,
                     :created_by, :updated_by)
             ON DUPLICATE KEY UPDATE updated_at = updated_at'
        );

        foreach ($dataRows as $i => $row) {
            $rowNum = $i + 2;
            $get    = fn(string $f): string => isset($fieldIdx[$f]) ? trim((string) ($row[$fieldIdx[$f]] ?? '')) : '';
            $nul    = fn(string $f): ?string => ($v = $get($f)) !== '' ? $v : null;

            $firstName = $get('first_name');
            $lastName  = $get('last_name');
            if ($firstName === '' || $lastName === '') {
                $errors[] = "Row $rowNum: first_name and last_name are required — skipped.";
                $skipped++;
                continue;
            }

            $gender = strtolower($get('gender'));
            if (!in_array($gender, ['male', 'female', 'other'], true)) {
                $gender = 'other';
            }

            $memberCode = $get('member_code');
            if ($memberCode === '') {
                $cs = $this->pdo->query("SELECT CONCAT('MBR-', DATE_FORMAT(NOW(), '%Y'), '-', LPAD(COALESCE(MAX(id),0)+1,4,'0')) FROM members");
                $memberCode = (string) $cs->fetchColumn();
            }

            $joinDate = $get('join_date');
            $joinDate = ($joinDate !== '' && strtotime($joinDate) !== false) ? date('Y-m-d', strtotime($joinDate)) : date('Y-m-d');

            $dob  = ($v = $get('date_of_birth'))  !== '' && strtotime($v) ? date('Y-m-d', strtotime($v)) : null;
            $bapt = ($v = $get('baptism_date'))    !== '' && strtotime($v) ? date('Y-m-d', strtotime($v)) : null;

            $status   = $get('member_status');
            $status   = in_array($status, ['active','inactive','transferred','deceased'], true) ? $status : 'active';
            $marital  = $get('marital_status');
            $marital  = in_array($marital, ['single','married','widowed','divorced'], true) ? $marital : null;

            try {
                $stmt->execute([
                    ':member_code'     => $memberCode,
                    ':first_name'      => $firstName,
                    ':last_name'       => $lastName,
                    ':gender'          => $gender,
                    ':phone'           => $get('phone'),
                    ':email'           => $nul('email'),
                    ':date_of_birth'   => $dob,
                    ':join_date'       => $joinDate,
                    ':member_status'   => $status,
                    ':physical_address'=> $nul('physical_address'),
                    ':ward'            => $nul('ward'),
                    ':district'        => $nul('district'),
                    ':region'          => $nul('region'),
                    ':marital_status'  => $marital,
                    ':baptism_date'    => $bapt,
                    ':notes'           => $nul('notes'),
                    ':created_by'      => $actorId,
                    ':updated_by'      => $actorId,
                ]);
                $inserted++;
            } catch (\PDOException $e) {
                if (str_contains($e->getMessage(), 'Duplicate entry')) {
                    $skipped++;
                } else {
                    $errors[] = "Row $rowNum: " . $e->getMessage();
                    $skipped++;
                }
            }
        }

        Audit::log($this->pdo, $actorId ? (int) $actorId : null, 'members', 'import', 'members', null, null, ['inserted' => $inserted, 'skipped' => $skipped], "Imported $inserted members from $ext file");

        Response::json([
            'success' => true,
            'message' => "Import complete: $inserted inserted, $skipped skipped.",
            'data'    => ['inserted' => $inserted, 'skipped' => $skipped, 'errors' => array_slice($errors, 0, 20)],
        ]);
    }

    private function parseCsv(string $filePath): array
    {
        $rows   = [];
        $handle = fopen($filePath, 'r');
        if ($handle === false) {
            throw new \RuntimeException('Cannot open CSV file.');
        }
        while (($row = fgetcsv($handle)) !== false) {
            if (!empty(array_filter($row, fn($v) => trim((string) $v) !== ''))) {
                $rows[] = $row;
            }
        }
        fclose($handle);
        return $rows;
    }

    private function parseXlsx(string $filePath): array
    {
        if (!class_exists('ZipArchive')) {
            throw new \RuntimeException('ZipArchive PHP extension is required to parse .xlsx files. Please use CSV instead.');
        }
        $zip = new \ZipArchive();
        if ($zip->open($filePath) !== true) {
            throw new \RuntimeException('Cannot open .xlsx file. The file may be corrupted.');
        }

        // Shared strings
        $sharedStrings = [];
        $ssXml = $zip->getFromName('xl/sharedStrings.xml');
        if ($ssXml !== false) {
            libxml_use_internal_errors(true);
            $ss = simplexml_load_string($ssXml);
            if ($ss) {
                foreach ($ss->si as $si) {
                    if (isset($si->t)) {
                        $sharedStrings[] = (string) $si->t;
                    } else {
                        $text = '';
                        foreach ($si->r as $r) {
                            $text .= (string) $r->t;
                        }
                        $sharedStrings[] = $text;
                    }
                }
            }
        }

        // Find first worksheet path
        $wsName = 'xl/worksheets/sheet1.xml';
        $wsXml  = $zip->getFromName($wsName);

        // Try workbook rels if sheet1 not found directly
        if ($wsXml === false) {
            $relsXml = $zip->getFromName('xl/_rels/workbook.xml.rels');
            if ($relsXml !== false) {
                $rels = simplexml_load_string($relsXml);
                if ($rels) {
                    foreach ($rels->Relationship as $rel) {
                        $t = (string) $rel['Type'];
                        if (str_contains($t, 'worksheet')) {
                            $wsName = 'xl/' . ltrim((string) $rel['Target'], '/');
                            $wsXml  = $zip->getFromName($wsName);
                            break;
                        }
                    }
                }
            }
        }
        $zip->close();

        if ($wsXml === false) {
            throw new \RuntimeException('Cannot read worksheet data from .xlsx file.');
        }

        libxml_use_internal_errors(true);
        $ws = simplexml_load_string($wsXml);
        if (!$ws) {
            throw new \RuntimeException('Worksheet XML is invalid.');
        }

        $rows = [];
        foreach ($ws->sheetData->row as $row) {
            $cells  = [];
            $maxCol = 0;
            foreach ($row->c as $cell) {
                $ref = (string) $cell['r'];
                preg_match('/^([A-Z]+)/i', $ref, $m);
                $letters  = strtoupper($m[1] ?? 'A');
                $colIndex = 0;
                for ($ci = 0; $ci < strlen($letters); $ci++) {
                    $colIndex = $colIndex * 26 + (ord($letters[$ci]) - 64);
                }
                $colIndex--;
                $type  = (string) ($cell['t'] ?? '');
                $value = isset($cell->v) ? (string) $cell->v : '';
                if ($type === 's') {
                    $value = $sharedStrings[(int) $value] ?? '';
                } elseif ($type === 'inlineStr') {
                    $value = isset($cell->is->t) ? (string) $cell->is->t : '';
                }
                $cells[$colIndex] = $value;
                if ($colIndex > $maxCol) {
                    $maxCol = $colIndex;
                }
            }
            $rowArr = [];
            for ($ci = 0; $ci <= $maxCol; $ci++) {
                $rowArr[] = $cells[$ci] ?? '';
            }
            if (!empty(array_filter($rowArr, fn($v) => trim((string) $v) !== ''))) {
                $rows[] = $rowArr;
            }
        }
        return $rows;
    }

    /* ───── Events ───── */

    public function listEvents(): void
    {
        $month = trim((string) ($_GET['month'] ?? ''));
        $type  = trim((string) ($_GET['type'] ?? ''));
        $group = trim((string) ($_GET['group'] ?? ''));

        $sql = 'SELECT e.id, e.event_code, e.title, e.description, e.category, e.start_datetime, e.end_datetime,
                       e.venue, e.expected_attendance, e.status, e.budget_total, e.notes,
                       g.name AS target_group, u.full_name AS organizer_name
                FROM `events` e
                LEFT JOIN `groups` g ON g.id = e.target_group_id
                LEFT JOIN users u ON u.id = e.organizer_user_id
                WHERE 1=1';

        $params = [];

        if ($month !== '' && preg_match('/^\d{4}-\d{2}$/', $month) === 1) {
            $sql .= ' AND DATE_FORMAT(e.start_datetime, "%Y-%m") = :month';
            $params[':month'] = $month;
        }

        if ($type !== '') {
            $sql .= ' AND e.category = :category';
            $params[':category'] = $type;
        }

        if ($group !== '' && ctype_digit($group)) {
            $sql .= ' AND e.target_group_id = :group_id';
            $params[':group_id'] = (int) $group;
        }

        $sql .= ' ORDER BY e.start_datetime ASC LIMIT 400';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll();

        Response::json(['success' => true, 'message' => 'Events list', 'data' => $rows]);
    }

    public function calendarEvents(): void
    {
        $month = trim((string) ($_GET['month'] ?? date('Y-m')));
        if (preg_match('/^\d{4}-\d{2}$/', $month) !== 1) {
            Response::json(['success' => false, 'message' => 'Invalid month format. Use YYYY-MM'], 422);
        }

        $monthStart = $month . '-01 00:00:00';
        $monthEnd = date('Y-m-t 23:59:59', strtotime($monthStart));

        $stmt = $this->pdo->prepare(
            'SELECT e.id, e.event_code, e.title, e.category, e.start_datetime, e.end_datetime, e.status,
                e.target_group_id, e.notes,
                    e.venue, e.expected_attendance, e.budget_total, g.name AS target_group
             FROM `events` e
             LEFT JOIN `groups` g ON g.id = e.target_group_id
             WHERE e.start_datetime BETWEEN :start_at AND :end_at
             ORDER BY e.start_datetime ASC'
        );
        $stmt->execute([
            ':start_at' => $monthStart,
            ':end_at' => $monthEnd,
        ]);

        $rows = $stmt->fetchAll();
        $normalized = array_map(function (array $event): array {
            $kind = $this->resolveEventKind((string) $event['category'], (string) ($event['notes'] ?? ''));
            $event['kind'] = $kind;
            $event['is_system'] = false;
            $event['is_editable'] = true;
            $event['tag'] = $this->kindTag($kind);
            return $event;
        }, $rows);

        $calendarEvents = array_merge($normalized, $this->buildSystemChurchEvents($month));
        usort($calendarEvents, fn (array $a, array $b): int => strcmp((string) $a['start_datetime'], (string) $b['start_datetime']));

        Response::json([
            'success' => true,
            'message' => 'Calendar events',
            'data' => [
                'month' => $month,
                'events' => $calendarEvents,
            ],
        ]);
    }

    public function createEvent(array $input): void
    {
        $required = ['title', 'event_type', 'date', 'time'];
        foreach ($required as $field) {
            if (empty($input[$field])) {
                Response::json(['success' => false, 'message' => $field . ' is required'], 422);
            }
        }

        $eventType = trim((string) $input['event_type']);
        $categoryMap = [
            'service' => 'conference',
            'seminar' => 'seminar',
            'meeting' => 'other',
            'appointment' => 'other',
        ];
        $category = $categoryMap[$eventType] ?? 'other';

        $date = trim((string) $input['date']);
        $time = trim((string) $input['time']);
        $startDatetime = date('Y-m-d H:i:s', strtotime($date . ' ' . $time));
        if ($startDatetime === false || $startDatetime === '1970-01-01 00:00:00') {
            Response::json(['success' => false, 'message' => 'Invalid date/time value'], 422);
        }

        $durationHours = isset($input['duration_hours']) ? (float) $input['duration_hours'] : 2.0;
        if ($durationHours <= 0 || $durationHours > 24) {
            $durationHours = 2.0;
        }
        $endDatetime = date('Y-m-d H:i:s', strtotime($startDatetime . ' +' . $durationHours . ' hour'));

        $codeStmt = $this->pdo->query("SELECT CONCAT('EVT-', DATE_FORMAT(NOW(), '%Y'), '-', LPAD(COALESCE(MAX(id), 0) + 1, 3, '0')) FROM `events`");
        $eventCode = (string) $codeStmt->fetchColumn();

        $organizerUserId = isset($input['organizer_user_id']) && $input['organizer_user_id'] !== '' ? (int) $input['organizer_user_id'] : null;
        $targetGroupId = isset($input['target_group_id']) && $input['target_group_id'] !== '' ? (int) $input['target_group_id'] : null;
        $expectedAttendance = isset($input['expected_attendance']) && $input['expected_attendance'] !== '' ? (int) $input['expected_attendance'] : null;
        $budget = isset($input['budget']) && $input['budget'] !== '' ? (float) $input['budget'] : 0.0;
        $sendSms = !empty($input['send_sms']);
        $sendEmail = !empty($input['send_email']);

        $notes = trim((string) ($input['description'] ?? ''));
        if ($eventType === 'appointment') {
            $notes .= ($notes !== '' ? "\n" : '') . '[event_subtype:appointment]';
        }
        if ($eventType === 'appointment' && !empty($input['appointment_with'])) {
            $apptWith = trim((string) $input['appointment_with']);
            if ($apptWith !== '') {
                $notes .= ($notes !== '' ? "\n" : '') . '[appointment_with:' . $apptWith . ']';
            }
        }
        if ($sendSms || $sendEmail) {
            $notes .= "\n\nNotification preferences: "
                . ($sendEmail ? 'email=on' : 'email=off')
                . ', '
                . ($sendSms ? 'sms=on' : 'sms=off');
        }

        $stmt = $this->pdo->prepare(
            'INSERT INTO `events` (event_code, title, description, category, start_datetime, end_datetime, venue,
                                   organizer_user_id, target_group_id, expected_attendance, status, budget_total, notes)
             VALUES (:event_code, :title, :description, :category, :start_datetime, :end_datetime, :venue,
                     :organizer_user_id, :target_group_id, :expected_attendance, :status, :budget_total, :notes)'
        );
        $stmt->execute([
            ':event_code' => $eventCode,
            ':title' => trim((string) $input['title']),
            ':description' => trim((string) ($input['description'] ?? '')),
            ':category' => $category,
            ':start_datetime' => $startDatetime,
            ':end_datetime' => $endDatetime,
            ':venue' => trim((string) ($input['location'] ?? '')),
            ':organizer_user_id' => $organizerUserId,
            ':target_group_id' => $targetGroupId,
            ':expected_attendance' => $expectedAttendance,
            ':status' => 'planned',
            ':budget_total' => $budget,
            ':notes' => $notes !== '' ? $notes : null,
        ]);

        $eventId = (int) $this->pdo->lastInsertId();
        $user = Auth::user();
        $actorId = isset($user['id']) ? (int) $user['id'] : null;

        if ($budget > 0) {
            $budgetStmt = $this->pdo->prepare(
                'INSERT INTO event_budget_items (event_id, item_type, item_name, planned_amount, actual_amount, notes)
                 VALUES (:event_id, :item_type, :item_name, :planned_amount, :actual_amount, :notes)'
            );
            $budgetStmt->execute([
                ':event_id' => $eventId,
                ':item_type' => 'expense',
                ':item_name' => 'Planned Event Budget',
                ':planned_amount' => $budget,
                ':actual_amount' => 0,
                ':notes' => 'Auto-created from quick event form',
            ]);
        }

        if ($sendSms && $actorId !== null) {
            $smsStmt = $this->pdo->prepare(
                'INSERT INTO sms_logs (recipient_type, group_id, phone, message_text, message_type, provider, delivery_status, event_id, sent_by, sent_at)
                 VALUES (:recipient_type, :group_id, :phone, :message_text, :message_type, :provider, :delivery_status, :event_id, :sent_by, :sent_at)'
            );
            $smsStmt->execute([
                ':recipient_type' => $targetGroupId !== null ? 'group' : 'custom',
                ':group_id' => $targetGroupId,
                ':phone' => 'N/A',
                ':message_text' => 'Event reminder: ' . trim((string) $input['title']) . ' on ' . date('d M Y H:i', strtotime($startDatetime)),
                ':message_type' => 'event_reminder',
                ':provider' => 'internal',
                ':delivery_status' => 'queued',
                ':event_id' => $eventId,
                ':sent_by' => $actorId,
                ':sent_at' => date('Y-m-d H:i:s'),
            ]);
        }

        Audit::log($this->pdo, $actorId, 'events', 'create', 'events', $eventId, null, $input, 'Created event from quick modal');

        Response::json([
            'success' => true,
            'message' => 'Event created successfully',
            'data' => [
                'id' => $eventId,
                'event_code' => $eventCode,
            ],
        ], 201);
    }

    public function eventDetails(int $eventId): void
    {
        $eventStmt = $this->pdo->prepare(
            'SELECT e.id, e.event_code, e.title, e.description, e.category, e.start_datetime, e.end_datetime,
                    e.venue, e.expected_attendance, e.status, e.budget_total, e.notes,
                    u.full_name AS organizer_name, g.name AS target_group
             FROM `events` e
             LEFT JOIN users u ON u.id = e.organizer_user_id
             LEFT JOIN `groups` g ON g.id = e.target_group_id
             WHERE e.id = :id LIMIT 1'
        );
        $eventStmt->execute([':id' => $eventId]);
        $event = $eventStmt->fetch();
        if (!$event) {
            Response::json(['success' => false, 'message' => 'Event not found'], 404);
        }

        $budgetStmt = $this->pdo->prepare(
            'SELECT id, item_type, item_name, planned_amount, actual_amount, notes
             FROM event_budget_items
             WHERE event_id = :id
             ORDER BY id ASC'
        );
        $budgetStmt->execute([':id' => $eventId]);
        $budgetItems = $budgetStmt->fetchAll();

        $plannedBudget = (float) $event['budget_total'];
        $actualExpenses = 0.0;
        foreach ($budgetItems as $item) {
            if (($item['item_type'] ?? '') === 'expense') {
                $actualExpenses += (float) ($item['actual_amount'] ?? 0);
            }
        }

        $taskStmt = $this->pdo->prepare(
            'SELECT et.id, et.title, et.details, et.due_datetime, et.task_status, et.priority, u.full_name AS assigned_to
             FROM event_tasks et
             INNER JOIN users u ON u.id = et.assigned_to_user_id
             WHERE et.event_id = :id
             ORDER BY et.due_datetime IS NULL, et.due_datetime ASC, et.id ASC'
        );
        $taskStmt->execute([':id' => $eventId]);
        $tasks = $taskStmt->fetchAll();

        $attendanceTotalsStmt = $this->pdo->prepare(
            "SELECT
                SUM(CASE WHEN status='registered' THEN 1 ELSE 0 END) AS registered_count,
                SUM(CASE WHEN status='present' THEN 1 ELSE 0 END) AS present_count,
                SUM(CASE WHEN status='absent' THEN 1 ELSE 0 END) AS absent_count
             FROM event_attendance
             WHERE event_id = :id"
        );
        $attendanceTotalsStmt->execute([':id' => $eventId]);
        $attendanceTotals = $attendanceTotalsStmt->fetch() ?: [];

        $attendanceListStmt = $this->pdo->prepare(
            'SELECT ea.id, ea.status, ea.check_in_datetime, m.member_code,
                    CONCAT(m.first_name, " ", m.last_name) AS member_name, m.phone
             FROM event_attendance ea
             INNER JOIN members m ON m.id = ea.member_id
             WHERE ea.event_id = :id
             ORDER BY member_name ASC'
        );
        $attendanceListStmt->execute([':id' => $eventId]);
        $attendanceMembers = $attendanceListStmt->fetchAll();

        $commStmt = $this->pdo->prepare(
            'SELECT id, recipient_type, phone, message_text, message_type, provider, delivery_status, sent_at
             FROM sms_logs
             WHERE event_id = :id
             ORDER BY sent_at DESC, id DESC
             LIMIT 20'
        );
        $commStmt->execute([':id' => $eventId]);
        $communications = $commStmt->fetchAll();

        $financeStmt = $this->pdo->prepare(
            "SELECT
                SUM(CASE WHEN efl.relation_type='income' THEN fe.amount ELSE 0 END) AS income_total,
                SUM(CASE WHEN efl.relation_type='expense' THEN fe.amount ELSE 0 END) AS expense_total
             FROM event_finance_links efl
             INNER JOIN finance_entries fe ON fe.id = efl.finance_entry_id
             WHERE efl.event_id = :id"
        );
        $financeStmt->execute([':id' => $eventId]);
        $financials = $financeStmt->fetch() ?: [];
        $incomeTotal  = (float) ($financials['income_total'] ?? 0);
        $expenseTotal = (float) ($financials['expense_total'] ?? 0);

        $report = [
            'income_total' => $incomeTotal,
            'expense_total' => $expenseTotal,
            'net_total' => $incomeTotal - $expenseTotal,
            'final_summary' => $incomeTotal - $expenseTotal >= 0
                ? 'Event ended with positive balance.'
                : 'Event ended with budget overrun.',
        ];

        Response::json([
            'success' => true,
            'message' => 'Event details',
            'data' => [
                'overview' => $event,
                'budget' => [
                    'planned_budget' => $plannedBudget,
                    'actual_expenses' => $actualExpenses,
                    'remaining_balance' => $plannedBudget - $actualExpenses,
                    'items' => $budgetItems,
                ],
                'tasks' => $tasks,
                'attendance' => [
                    'registered_count' => (int) ($attendanceTotals['registered_count'] ?? 0),
                    'present_count' => (int) ($attendanceTotals['present_count'] ?? 0),
                    'absent_count' => (int) ($attendanceTotals['absent_count'] ?? 0),
                    'members' => $attendanceMembers,
                ],
                'communication' => [
                    'sms_logs' => $communications,
                    'email_note' => 'Email queue is simulated in this version.',
                ],
                'report' => $report,
            ],
        ]);
    }

    public function sendEventCommunication(int $eventId, array $input): void
    {
        $user = Auth::user();
        $actorId = isset($user['id']) ? (int) $user['id'] : null;
        if ($actorId === null) {
            Response::json(['success' => false, 'message' => 'Not authenticated'], 401);
        }

        $message = trim((string) ($input['message'] ?? ''));
        $sendSms = !empty($input['send_sms']);
        $sendEmail = !empty($input['send_email']);

        if ($message === '') {
            Response::json(['success' => false, 'message' => 'Message is required'], 422);
        }
        if (!$sendSms && !$sendEmail) {
            Response::json(['success' => false, 'message' => 'Select at least one channel (SMS or Email)'], 422);
        }

        $recipientsStmt = $this->pdo->prepare(
            'SELECT m.id, m.phone, m.email
             FROM event_attendance ea
             INNER JOIN members m ON m.id = ea.member_id
             WHERE ea.event_id = :id'
        );
        $recipientsStmt->execute([':id' => $eventId]);
        $recipients = $recipientsStmt->fetchAll();

        $smsCount = 0;
        if ($sendSms) {
            $smsStmt = $this->pdo->prepare(
                'INSERT INTO sms_logs (recipient_type, member_id, phone, message_text, message_type, provider, delivery_status, event_id, sent_by, sent_at)
                 VALUES (:recipient_type, :member_id, :phone, :message_text, :message_type, :provider, :delivery_status, :event_id, :sent_by, :sent_at)'
            );

            foreach ($recipients as $recipient) {
                if (empty($recipient['phone'])) {
                    continue;
                }
                $smsStmt->execute([
                    ':recipient_type' => 'member',
                    ':member_id' => (int) $recipient['id'],
                    ':phone' => (string) $recipient['phone'],
                    ':message_text' => $message,
                    ':message_type' => 'event_reminder',
                    ':provider' => 'internal',
                    ':delivery_status' => 'queued',
                    ':event_id' => $eventId,
                    ':sent_by' => $actorId,
                    ':sent_at' => date('Y-m-d H:i:s'),
                ]);
                $smsCount++;
            }
        }

        $emailCount = 0;
        if ($sendEmail) {
            foreach ($recipients as $recipient) {
                if (!empty($recipient['email'])) {
                    $emailCount++;
                }
            }
        }

        Audit::log($this->pdo, $actorId, 'events', 'communicate', 'events', $eventId, null, $input, 'Sent event communication');

        Response::json([
            'success' => true,
            'message' => 'Communication queued',
            'data' => [
                'sms_queued' => $smsCount,
                'email_prepared' => $emailCount,
            ],
        ]);
    }

    public function listGroups(): void
    {
        $rows = $this->pdo->query('SELECT id, name FROM `groups` WHERE is_active = 1 ORDER BY name ASC')->fetchAll();
        Response::json(['success' => true, 'message' => 'Groups list', 'data' => $rows]);
    }

    public function listUsers(): void
    {
        $rows = $this->pdo->query('SELECT id, full_name FROM users WHERE is_active = 1 ORDER BY full_name ASC')->fetchAll();
        Response::json(['success' => true, 'message' => 'Users list', 'data' => $rows]);
    }

    public function eventReport(int $eventId): void
    {
        $this->eventDetails($eventId);
    }

    /* ───── Attendance ───── */

    public function attendanceOverview(): void
    {
        $this->ensureAttendanceSnapshotsTable();

        $month = trim((string) ($_GET['month'] ?? date('Y-m')));
        if (preg_match('/^\d{4}-\d{2}$/', $month) !== 1) {
            Response::json(['success' => false, 'message' => 'Invalid month format. Use YYYY-MM'], 422);
        }

        $monthStart = $month . '-01';
        $monthEnd = date('Y-m-t', strtotime($monthStart));

        $monthlyStmt = $this->pdo->prepare(
            "SELECT
                COUNT(*) AS services_count,
                COALESCE(SUM(men_count), 0) AS men_total,
                COALESCE(SUM(women_count), 0) AS women_total,
                COALESCE(SUM(children_count), 0) AS children_total,
                COALESCE(SUM(youth_count), 0) AS youth_total,
                COALESCE(SUM(guests_count), 0) AS guests_total,
                COALESCE(SUM(total_count), 0) AS attendance_total
             FROM attendance_snapshots
             WHERE service_date BETWEEN :start_at AND :end_at"
        );
        $monthlyStmt->execute([
            ':start_at' => $monthStart,
            ':end_at' => $monthEnd,
        ]);
        $monthly = $monthlyStmt->fetch() ?: [];

        $latestStmt = $this->pdo->query(
            'SELECT id, service_date, service_name, service_type,
                    men_count, women_count, children_count, youth_count, guests_count, total_count,
                    notes, created_at
             FROM attendance_snapshots
             ORDER BY service_date DESC, id DESC
             LIMIT 1'
        );
        $latest = $latestStmt->fetch() ?: null;

        $trendStmt = $this->pdo->prepare(
            'SELECT service_date, total_count
             FROM attendance_snapshots
             WHERE service_date BETWEEN :start_at AND :end_at
             ORDER BY service_date ASC, id ASC'
        );
        $trendStmt->execute([
            ':start_at' => $monthStart,
            ':end_at' => $monthEnd,
        ]);
        $trendRows = $trendStmt->fetchAll();

        Response::json([
            'success' => true,
            'message' => 'Attendance overview',
            'data' => [
                'month' => $month,
                'summary' => [
                    'services_count' => (int) ($monthly['services_count'] ?? 0),
                    'men_total' => (int) ($monthly['men_total'] ?? 0),
                    'women_total' => (int) ($monthly['women_total'] ?? 0),
                    'children_total' => (int) ($monthly['children_total'] ?? 0),
                    'youth_total' => (int) ($monthly['youth_total'] ?? 0),
                    'guests_total' => (int) ($monthly['guests_total'] ?? 0),
                    'attendance_total' => (int) ($monthly['attendance_total'] ?? 0),
                ],
                'latest' => $latest,
                'trend' => $trendRows,
            ],
        ]);
    }

    public function listAttendanceSnapshots(): void
    {
        $this->ensureAttendanceSnapshotsTable();

        $month = trim((string) ($_GET['month'] ?? ''));
        $type = trim((string) ($_GET['type'] ?? ''));

        $sql = 'SELECT id, service_date, service_name, service_type,
                       men_count, women_count, children_count, youth_count, guests_count,
                       total_count, notes, created_at
                FROM attendance_snapshots
                WHERE 1=1';
        $params = [];

        if ($month !== '' && preg_match('/^\d{4}-\d{2}$/', $month) === 1) {
            $sql .= ' AND DATE_FORMAT(service_date, "%Y-%m") = :month';
            $params[':month'] = $month;
        }

        if ($type !== '') {
            $sql .= ' AND service_type = :type';
            $params[':type'] = $type;
        }

        $sql .= ' ORDER BY service_date DESC, id DESC LIMIT 300';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        Response::json([
            'success' => true,
            'message' => 'Attendance snapshots',
            'data' => $stmt->fetchAll(),
        ]);
    }

    public function recordAttendanceSnapshot(array $input): void
    {
        $this->ensureAttendanceSnapshotsTable();

        $required = ['service_date', 'service_name'];
        foreach ($required as $field) {
            if (empty($input[$field])) {
                Response::json(['success' => false, 'message' => $field . ' is required'], 422);
            }
        }

        $serviceDate = trim((string) $input['service_date']);
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $serviceDate) !== 1) {
            Response::json(['success' => false, 'message' => 'service_date must be YYYY-MM-DD'], 422);
        }

        $serviceType = trim((string) ($input['service_type'] ?? 'sunday_service'));
        $allowedTypes = ['sunday_service', 'midweek', 'prayer', 'youth_service', 'special', 'other'];
        if (!in_array($serviceType, $allowedTypes, true)) {
            $serviceType = 'other';
        }

        $men = max(0, (int) ($input['men_count'] ?? 0));
        $women = max(0, (int) ($input['women_count'] ?? 0));
        $children = max(0, (int) ($input['children_count'] ?? 0));
        $youth = max(0, (int) ($input['youth_count'] ?? 0));
        $guests = max(0, (int) ($input['guests_count'] ?? 0));
        $total = $men + $women + $children + $youth + $guests;

        if ($total <= 0) {
            Response::json(['success' => false, 'message' => 'Enter at least one attendance value'], 422);
        }

        $user = Auth::user();
        $actorId = isset($user['id']) ? (int) $user['id'] : null;

        $stmt = $this->pdo->prepare(
            'INSERT INTO attendance_snapshots (
                service_date, service_name, service_type,
                men_count, women_count, children_count, youth_count, guests_count,
                total_count, notes, created_by
             ) VALUES (
                :service_date, :service_name, :service_type,
                :men_count, :women_count, :children_count, :youth_count, :guests_count,
                :total_count, :notes, :created_by
             )'
        );
        $stmt->execute([
            ':service_date' => $serviceDate,
            ':service_name' => trim((string) $input['service_name']),
            ':service_type' => $serviceType,
            ':men_count' => $men,
            ':women_count' => $women,
            ':children_count' => $children,
            ':youth_count' => $youth,
            ':guests_count' => $guests,
            ':total_count' => $total,
            ':notes' => trim((string) ($input['notes'] ?? '')),
            ':created_by' => $actorId,
        ]);

        $snapshotId = (int) $this->pdo->lastInsertId();
        Audit::log($this->pdo, $actorId, 'attendance', 'create', 'attendance_snapshots', $snapshotId, null, [
            'service_date' => $serviceDate,
            'service_name' => trim((string) $input['service_name']),
            'service_type' => $serviceType,
            'total_count' => $total,
        ], 'Recorded aggregate attendance snapshot');

        Response::json([
            'success' => true,
            'message' => 'Attendance recorded',
            'data' => [
                'id' => $snapshotId,
                'total_count' => $total,
            ],
        ], 201);
    }

    private function ensureAttendanceSnapshotsTable(): void
    {
        $this->pdo->exec(
            'CREATE TABLE IF NOT EXISTS attendance_snapshots (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                service_date DATE NOT NULL,
                service_name VARCHAR(150) NOT NULL,
                service_type ENUM("sunday_service", "midweek", "prayer", "youth_service", "special", "other") NOT NULL DEFAULT "sunday_service",
                men_count INT UNSIGNED NOT NULL DEFAULT 0,
                women_count INT UNSIGNED NOT NULL DEFAULT 0,
                children_count INT UNSIGNED NOT NULL DEFAULT 0,
                youth_count INT UNSIGNED NOT NULL DEFAULT 0,
                guests_count INT UNSIGNED NOT NULL DEFAULT 0,
                total_count INT UNSIGNED NOT NULL DEFAULT 0,
                notes VARCHAR(255) NULL,
                created_by BIGINT UNSIGNED NULL,
                created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_attendance_snapshots_date (service_date),
                INDEX idx_attendance_snapshots_type (service_type),
                CONSTRAINT fk_attendance_snapshots_created_by FOREIGN KEY (created_by) REFERENCES users(id)
                    ON UPDATE CASCADE ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
        );
    }

    /* ───── Assets ───── */

    public function assetsOverview(): void
    {
        $totals = $this->pdo->query(
            "SELECT
                COUNT(*) AS total_assets,
                COALESCE(SUM(COALESCE(purchase_value, 0)), 0) AS total_value,
                SUM(condition_status = 'excellent') AS excellent_count,
                SUM(condition_status = 'good') AS good_count,
                SUM(condition_status = 'fair') AS fair_count,
                SUM(condition_status = 'poor') AS poor_count,
                SUM(condition_status = 'retired') AS retired_count,
                SUM(is_active = 1) AS active_count
             FROM assets"
        )->fetch() ?: [];

        $dueStmt = $this->pdo->prepare(
            "SELECT COUNT(*)
             FROM maintenance_logs
             WHERE next_due_date IS NOT NULL
               AND next_due_date <= CURRENT_DATE"
        );
        $dueStmt->execute();
        $dueMaintenance = (int) $dueStmt->fetchColumn();

        Response::json([
            'success' => true,
            'message' => 'Assets overview',
            'data' => [
                'total_assets' => (int) ($totals['total_assets'] ?? 0),
                'active_count' => (int) ($totals['active_count'] ?? 0),
                'total_value' => (float) ($totals['total_value'] ?? 0),
                'conditions' => [
                    'excellent' => (int) ($totals['excellent_count'] ?? 0),
                    'good' => (int) ($totals['good_count'] ?? 0),
                    'fair' => (int) ($totals['fair_count'] ?? 0),
                    'poor' => (int) ($totals['poor_count'] ?? 0),
                    'retired' => (int) ($totals['retired_count'] ?? 0),
                ],
                'due_maintenance' => $dueMaintenance,
            ],
        ]);
    }

    public function listAssets(): void
    {
        $search = trim((string) ($_GET['search'] ?? ''));
        $condition = trim((string) ($_GET['condition'] ?? ''));
        $category = trim((string) ($_GET['category'] ?? ''));

        $sql = 'SELECT a.id, a.asset_tag, a.name, a.category, a.purchase_date, a.purchase_value,
                       a.condition_status, a.current_location, a.assigned_to_user_id, a.assigned_event_id,
                       a.warranty_expiry, a.is_active, a.notes,
                       u.full_name AS assigned_user_name,
                       e.title AS assigned_event_title,
                       lm.latest_maintenance_date,
                       lm.latest_next_due_date,
                       lm.latest_maintenance_cost
                FROM assets a
                LEFT JOIN users u ON u.id = a.assigned_to_user_id
                LEFT JOIN `events` e ON e.id = a.assigned_event_id
                LEFT JOIN (
                    SELECT ml.asset_id,
                           MAX(ml.maintenance_date) AS latest_maintenance_date,
                           SUBSTRING_INDEX(GROUP_CONCAT(ml.next_due_date ORDER BY ml.maintenance_date DESC), ",", 1) AS latest_next_due_date,
                           SUBSTRING_INDEX(GROUP_CONCAT(ml.maintenance_cost ORDER BY ml.maintenance_date DESC), ",", 1) AS latest_maintenance_cost
                    FROM maintenance_logs ml
                    GROUP BY ml.asset_id
                ) lm ON lm.asset_id = a.id
                WHERE 1=1';

        $params = [];

        if ($search !== '') {
            $sql .= ' AND (a.asset_tag LIKE :s1 OR a.name LIKE :s2 OR a.current_location LIKE :s3)';
            $like = '%' . $search . '%';
            $params[':s1'] = $like;
            $params[':s2'] = $like;
            $params[':s3'] = $like;
        }

        if ($condition !== '' && in_array($condition, ['excellent', 'good', 'fair', 'poor', 'retired'], true)) {
            $sql .= ' AND a.condition_status = :condition';
            $params[':condition'] = $condition;
        }

        if ($category !== '') {
            $sql .= ' AND a.category = :category';
            $params[':category'] = $category;
        }

        $sql .= ' ORDER BY a.created_at DESC, a.id DESC LIMIT 500';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        Response::json([
            'success' => true,
            'message' => 'Assets list',
            'data' => $stmt->fetchAll(),
        ]);
    }

    public function createAsset(array $input): void
    {
        $required = ['name', 'category', 'current_location'];
        foreach ($required as $field) {
            if (empty($input[$field])) {
                Response::json(['success' => false, 'message' => $field . ' is required'], 422);
            }
        }

        $assetTag = trim((string) ($input['asset_tag'] ?? ''));
        if ($assetTag === '') {
            $tagStmt = $this->pdo->query(
                "SELECT CONCAT('AST-', DATE_FORMAT(NOW(), '%Y'), '-', LPAD(COALESCE(MAX(id), 0) + 1, 4, '0')) FROM assets"
            );
            $assetTag = (string) $tagStmt->fetchColumn();
        }

        $condition = trim((string) ($input['condition_status'] ?? 'good'));
        if (!in_array($condition, ['excellent', 'good', 'fair', 'poor', 'retired'], true)) {
            $condition = 'good';
        }

        $user = Auth::user();
        $actorId = isset($user['id']) ? (int) $user['id'] : null;

        $stmt = $this->pdo->prepare(
            'INSERT INTO assets (
                asset_tag, name, category, purchase_date, purchase_value,
                condition_status, current_location, assigned_to_user_id, assigned_event_id,
                warranty_expiry, is_active, notes
            ) VALUES (
                :asset_tag, :name, :category, :purchase_date, :purchase_value,
                :condition_status, :current_location, :assigned_to_user_id, :assigned_event_id,
                :warranty_expiry, :is_active, :notes
            )'
        );

        $stmt->execute([
            ':asset_tag' => $assetTag,
            ':name' => trim((string) $input['name']),
            ':category' => trim((string) $input['category']),
            ':purchase_date' => !empty($input['purchase_date']) ? $input['purchase_date'] : null,
            ':purchase_value' => isset($input['purchase_value']) && $input['purchase_value'] !== '' ? (float) $input['purchase_value'] : null,
            ':condition_status' => $condition,
            ':current_location' => trim((string) $input['current_location']),
            ':assigned_to_user_id' => isset($input['assigned_to_user_id']) && $input['assigned_to_user_id'] !== '' ? (int) $input['assigned_to_user_id'] : null,
            ':assigned_event_id' => isset($input['assigned_event_id']) && $input['assigned_event_id'] !== '' ? (int) $input['assigned_event_id'] : null,
            ':warranty_expiry' => !empty($input['warranty_expiry']) ? $input['warranty_expiry'] : null,
            ':is_active' => isset($input['is_active']) ? (int) ((int) $input['is_active'] === 1) : 1,
            ':notes' => trim((string) ($input['notes'] ?? '')),
        ]);

        $assetId = (int) $this->pdo->lastInsertId();
        Audit::log($this->pdo, $actorId, 'assets', 'create', 'assets', $assetId, null, [
            'asset_tag' => $assetTag,
            'name' => trim((string) $input['name']),
        ], 'Created asset record');

        Response::json([
            'success' => true,
            'message' => 'Asset created',
            'data' => ['id' => $assetId, 'asset_tag' => $assetTag],
        ], 201);
    }

    public function updateAsset(int $assetId, array $input): void
    {
        $allowed = [
            'name', 'category', 'purchase_date', 'purchase_value', 'condition_status', 'current_location',
            'assigned_to_user_id', 'assigned_event_id', 'warranty_expiry', 'is_active', 'notes',
        ];

        $set = [];
        $params = [':id' => $assetId];

        foreach ($allowed as $field) {
            if (array_key_exists($field, $input)) {
                $set[] = "`$field` = :$field";

                $value = $input[$field];
                if (in_array($field, ['assigned_to_user_id', 'assigned_event_id'], true)) {
                    $value = ($value !== '' && $value !== null) ? (int) $value : null;
                } elseif ($field === 'is_active') {
                    $value = (int) ((int) $value === 1);
                } elseif ($field === 'purchase_value' && $value !== '' && $value !== null) {
                    $value = (float) $value;
                } elseif ($value === '') {
                    $value = null;
                }

                $params[":" . $field] = $value;
            }
        }

        if (empty($set)) {
            Response::json(['success' => false, 'message' => 'Nothing to update'], 422);
        }

        $stmt = $this->pdo->prepare('UPDATE assets SET ' . implode(', ', $set) . ' WHERE id = :id');
        $stmt->execute($params);

        $user = Auth::user();
        $actorId = isset($user['id']) ? (int) $user['id'] : null;
        Audit::log($this->pdo, $actorId, 'assets', 'update', 'assets', $assetId, null, $input, 'Updated asset record');

        Response::json(['success' => true, 'message' => 'Asset updated']);
    }

    public function listAssetMaintenance(int $assetId): void
    {
        $stmt = $this->pdo->prepare(
            'SELECT ml.id, ml.maintenance_type, ml.issue_description, ml.action_taken, ml.service_provider,
                    ml.maintenance_cost, ml.maintenance_date, ml.next_due_date,
                    u.full_name AS created_by_name
             FROM maintenance_logs ml
             LEFT JOIN users u ON u.id = ml.created_by
             WHERE ml.asset_id = :asset_id
             ORDER BY ml.maintenance_date DESC, ml.id DESC
             LIMIT 200'
        );
        $stmt->execute([':asset_id' => $assetId]);

        Response::json([
            'success' => true,
            'message' => 'Asset maintenance logs',
            'data' => $stmt->fetchAll(),
        ]);
    }

    public function createAssetMaintenance(int $assetId, array $input): void
    {
        $required = ['maintenance_type', 'action_taken', 'maintenance_date'];
        foreach ($required as $field) {
            if (empty($input[$field])) {
                Response::json(['success' => false, 'message' => $field . ' is required'], 422);
            }
        }

        $maintenanceType = trim((string) $input['maintenance_type']);
        if (!in_array($maintenanceType, ['routine', 'repair', 'inspection', 'replacement'], true)) {
            Response::json(['success' => false, 'message' => 'Invalid maintenance_type'], 422);
        }

        $user = Auth::user();
        $actorId = isset($user['id']) ? (int) $user['id'] : null;

        $stmt = $this->pdo->prepare(
            'INSERT INTO maintenance_logs (
                asset_id, maintenance_type, issue_description, action_taken,
                service_provider, maintenance_cost, maintenance_date, next_due_date, created_by
             ) VALUES (
                :asset_id, :maintenance_type, :issue_description, :action_taken,
                :service_provider, :maintenance_cost, :maintenance_date, :next_due_date, :created_by
             )'
        );
        $stmt->execute([
            ':asset_id' => $assetId,
            ':maintenance_type' => $maintenanceType,
            ':issue_description' => trim((string) ($input['issue_description'] ?? '')),
            ':action_taken' => trim((string) $input['action_taken']),
            ':service_provider' => trim((string) ($input['service_provider'] ?? '')),
            ':maintenance_cost' => isset($input['maintenance_cost']) && $input['maintenance_cost'] !== '' ? (float) $input['maintenance_cost'] : 0,
            ':maintenance_date' => $input['maintenance_date'],
            ':next_due_date' => !empty($input['next_due_date']) ? $input['next_due_date'] : null,
            ':created_by' => $actorId,
        ]);

        if (!empty($input['condition_status']) && in_array((string) $input['condition_status'], ['excellent', 'good', 'fair', 'poor', 'retired'], true)) {
            $assetUpdateStmt = $this->pdo->prepare('UPDATE assets SET condition_status = :condition_status WHERE id = :asset_id');
            $assetUpdateStmt->execute([
                ':condition_status' => (string) $input['condition_status'],
                ':asset_id' => $assetId,
            ]);
        }

        $maintenanceId = (int) $this->pdo->lastInsertId();
        Audit::log($this->pdo, $actorId, 'assets', 'maintenance', 'maintenance_logs', $maintenanceId, null, [
            'asset_id' => $assetId,
            'maintenance_type' => $maintenanceType,
        ], 'Recorded asset maintenance log');

        Response::json([
            'success' => true,
            'message' => 'Maintenance log created',
            'data' => ['id' => $maintenanceId],
        ], 201);
    }

    /* ───── Finance ───── */

    public function listFinanceEntries(): void
    {
        $rows = $this->pdo->query(
            'SELECT fe.id, fe.entry_no, fe.entry_date, fc.name AS category_name, fc.category_type,
                    fe.amount, fe.payment_method, fe.description
             FROM finance_entries fe
             INNER JOIN finance_categories fc ON fc.id = fe.category_id
             ORDER BY fe.entry_date DESC, fe.id DESC LIMIT 200'
        )->fetchAll();
        Response::json(['success' => true, 'message' => 'Finance entries', 'data' => $rows]);
    }

    public function listFinanceCategories(): void
    {
        $rows = $this->pdo->query('SELECT id, name, category_type FROM finance_categories ORDER BY category_type, name')->fetchAll();
        Response::json(['success' => true, 'message' => 'Finance categories', 'data' => $rows]);
    }

    public function createFinanceEntry(array $input): void
    {
        $required = ['entry_no', 'entry_date', 'category_id', 'amount', 'payment_method', 'description'];
        foreach ($required as $field) {
            if (empty($input[$field])) {
                Response::json(['success' => false, 'message' => $field . ' is required'], 422);
            }
        }

        $user = Auth::user();
        if (!$user) {
            Response::json(['success' => false, 'message' => 'Not authenticated'], 401);
        }

        $stmt = $this->pdo->prepare(
            'INSERT INTO finance_entries (entry_no, entry_date, category_id, amount, payment_method, source_type, source_id, event_id, member_id, supplier_id, purchase_order_id, description, recorded_by)
             VALUES (:entry_no, :entry_date, :category_id, :amount, :payment_method, :source_type, :source_id, :event_id, :member_id, :supplier_id, :purchase_order_id, :description, :recorded_by)'
        );

        $stmt->execute([
            ':entry_no'          => $input['entry_no'],
            ':entry_date'        => $input['entry_date'],
            ':category_id'       => (int) $input['category_id'],
            ':amount'            => (float) $input['amount'],
            ':payment_method'    => $input['payment_method'],
            ':source_type'       => $input['source_type'] ?? 'manual',
            ':source_id'         => isset($input['source_id']) && $input['source_id'] !== '' ? (int) $input['source_id'] : null,
            ':event_id'          => isset($input['event_id']) && $input['event_id'] !== '' ? (int) $input['event_id'] : null,
            ':member_id'         => isset($input['member_id']) && $input['member_id'] !== '' ? (int) $input['member_id'] : null,
            ':supplier_id'       => isset($input['supplier_id']) && $input['supplier_id'] !== '' ? (int) $input['supplier_id'] : null,
            ':purchase_order_id' => isset($input['purchase_order_id']) && $input['purchase_order_id'] !== '' ? (int) $input['purchase_order_id'] : null,
            ':description'       => $input['description'],
            ':recorded_by'       => (int) $user['id'],
        ]);

        $id = (int) $this->pdo->lastInsertId();
        Audit::log($this->pdo, (int) $user['id'], 'finance', 'create', 'finance_entries', $id, null, $input, 'Recorded finance entry');

        Response::json(['success' => true, 'message' => 'Finance entry created', 'data' => ['id' => $id]], 201);
    }

    /* ───── Fallback ───── */

    public function notFound(): void
    {
        Response::json(['success' => false, 'message' => 'Endpoint not found'], 404);
    }

    private function resolveEventKind(string $category, string $notes = ''): string
    {
        if (str_contains(strtolower($notes), '[event_subtype:appointment]')) {
            return 'appointment';
        }

        return match ($category) {
            'youth' => 'youth',
            'conference', 'seminar', 'choir' => 'worship',
            default => 'special',
        };
    }

    private function kindTag(string $kind): string
    {
        return match ($kind) {
            'worship' => 'Worship',
            'youth' => 'Youth',
            'appointment' => 'Appointment',
            default => 'Special',
        };
    }

    private function buildSystemChurchEvents(string $month): array
    {
        $year = (int) substr($month, 0, 4);
        $monthNum = (int) substr($month, 5, 2);
        $events = [];

        foreach ($this->sundaysInMonth($year . '-' . str_pad((string) $monthNum, 2, '0', STR_PAD_LEFT) . '-01') as $sundayDate) {
            $events[] = $this->buildSystemEvent($sundayDate, 'Worship Service', 'worship', 'Every Sunday worship gathering', '09:00:00', '11:30:00');
        }

        $easterDate = date('Y-m-d', easter_date($year));
        $goodFriday = date('Y-m-d', strtotime($easterDate . ' -2 days'));

        $fixedSpecials = [
            [$goodFriday, 'Good Friday Service'],
            [$easterDate, 'Easter Sunday Celebration'],
            [$year . '-12-25', 'Christmas Service'],
            [$year . '-01-01', 'New Year Service'],
        ];

        foreach ($fixedSpecials as [$date, $title]) {
            if ((int) substr($date, 5, 2) === $monthNum) {
                $events[] = $this->buildSystemEvent($date, $title, 'special', 'Auto-generated church calendar event', '09:00:00', '11:30:00');
            }
        }

        return $events;
    }

    private function buildSystemEvent(string $date, string $title, string $kind, string $notes, string $startTime, string $endTime): array
    {
        return [
            'id' => 'sys-' . str_replace('-', '', $date) . '-' . strtolower(str_replace(' ', '-', $title)),
            'event_code' => 'SYS-' . str_replace('-', '', $date),
            'title' => $title,
            'category' => 'system',
            'kind' => $kind,
            'tag' => $this->kindTag($kind),
            'start_datetime' => $date . ' ' . $startTime,
            'end_datetime' => $date . ' ' . $endTime,
            'status' => 'system',
            'target_group_id' => null,
            'venue' => 'Main Sanctuary',
            'expected_attendance' => null,
            'budget_total' => 0,
            'target_group' => 'All Church',
            'is_system' => true,
            'is_editable' => false,
            'system_notes' => $notes,
        ];
    }

    private function sundaysInMonth(string $monthStart): array
    {
        $cursor = strtotime(date('Y-m-01', strtotime($monthStart)));
        $month = date('m', $cursor);
        $sundays = [];

        while (date('m', $cursor) === $month) {
            if ((int) date('w', $cursor) === 0) {
                $sundays[] = date('Y-m-d', $cursor);
            }
            $cursor = strtotime('+1 day', $cursor);
        }

        return $sundays;
    }
}
