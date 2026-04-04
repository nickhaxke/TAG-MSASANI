<?php

declare(strict_types=1);

date_default_timezone_set('Africa/Dar_es_Salaam');

$config = require __DIR__ . '/../app/config.php';

define('BASE_URL', $config['app']['base_path']);

require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/Auth.php';
require_once __DIR__ . '/../app/core/Audit.php';
require_once __DIR__ . '/../app/core/Response.php';
require_once __DIR__ . '/../app/controllers/PageController.php';
require_once __DIR__ . '/../app/controllers/ApiController.php';

use App\Controllers\ApiController;
use App\Controllers\PageController;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Response;

Auth::boot($config);

try {
    $pdo = Database::connection($config);
} catch (\Throwable $e) {
    http_response_code(500);
    echo '<h1>Database connection failed</h1>';
    if ($config['app']['debug']) {
        echo '<pre>' . htmlspecialchars($e->getMessage()) . '</pre>';
    }
    exit;
}

$pageController = new PageController($pdo);
$apiController  = new ApiController($pdo);

$uri    = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Strip base path to get clean route
if (str_starts_with($uri, BASE_URL)) {
    $uri = substr($uri, strlen(BASE_URL));
}
if ($uri === '' || $uri === false) {
    $uri = '/';
}
// Ensure leading slash
if ($uri[0] !== '/') {
    $uri = '/' . $uri;
}

// ────── API routes (no HTML, JSON only) ──────
if (str_starts_with($uri, '/api/v1/')) {
    header('Content-Type: application/json; charset=utf-8');

    // Auth-exempt API endpoints
    $authExempt = ['/api/v1/auth/login', '/api/v1/auth/forgot-password', '/api/v1/auth/reset-password'];

    if (!Auth::check() && !in_array($uri, $authExempt, true)) {
        Response::json(['success' => false, 'message' => 'Unauthenticated'], 401);
    }

    // CSRF validation for state-changing API requests
    if (in_array($method, ['POST', 'PUT', 'DELETE'], true)) {
        $csrfToken = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        if (!Auth::validateCsrfToken($csrfToken)) {
            Response::json(['success' => false, 'message' => 'Invalid CSRF token'], 403);
        }
    }

    match (true) {
        $method === 'POST' && $uri === '/api/v1/auth/login'
            => $apiController->login(json_decode((string) file_get_contents('php://input'), true) ?: $_POST),

        $method === 'POST' && $uri === '/api/v1/auth/forgot-password'
            => $apiController->forgotPassword(json_decode((string) file_get_contents('php://input'), true) ?: $_POST),

        $method === 'POST' && $uri === '/api/v1/auth/reset-password'
            => $apiController->resetPassword(json_decode((string) file_get_contents('php://input'), true) ?: $_POST),

        $method === 'GET' && $uri === '/api/v1/dashboard/stats'
            => $apiController->dashboardStats(),

        $method === 'GET' && $uri === '/api/v1/dashboard/insights'
            => $apiController->dashboardInsights(),

        $method === 'GET' && $uri === '/api/v1/members'
            => $apiController->listMembers(),

        $method === 'GET' && $uri === '/api/v1/members/stats'
            => $apiController->memberStats(),

        $method === 'POST' && $uri === '/api/v1/members'
            => $apiController->createMember(json_decode((string) file_get_contents('php://input'), true) ?: $_POST),

        $method === 'POST' && $uri === '/api/v1/members/import'
            => $apiController->importMembers(),

        $method === 'PUT' && preg_match('#^/api/v1/members/(\d+)$#', $uri, $m) === 1
            => $apiController->updateMember((int) $m[1], json_decode((string) file_get_contents('php://input'), true) ?: []),

        $method === 'GET' && $uri === '/api/v1/attendance/overview'
            => $apiController->attendanceOverview(),

        $method === 'GET' && $uri === '/api/v1/attendance/snapshots'
            => $apiController->listAttendanceSnapshots(),

        $method === 'POST' && $uri === '/api/v1/attendance/snapshots'
            => $apiController->recordAttendanceSnapshot(json_decode((string) file_get_contents('php://input'), true) ?: $_POST),

        $method === 'GET' && $uri === '/api/v1/assets/overview'
            => $apiController->assetsOverview(),

        $method === 'GET' && $uri === '/api/v1/assets'
            => $apiController->listAssets(),

        $method === 'POST' && $uri === '/api/v1/assets'
            => $apiController->createAsset(json_decode((string) file_get_contents('php://input'), true) ?: $_POST),

        $method === 'PUT' && preg_match('#^/api/v1/assets/(\d+)$#', $uri, $m) === 1
            => $apiController->updateAsset((int) $m[1], json_decode((string) file_get_contents('php://input'), true) ?: []),

        $method === 'GET' && preg_match('#^/api/v1/assets/(\d+)/maintenance$#', $uri, $m) === 1
            => $apiController->listAssetMaintenance((int) $m[1]),

        $method === 'POST' && preg_match('#^/api/v1/assets/(\d+)/maintenance$#', $uri, $m) === 1
            => $apiController->createAssetMaintenance((int) $m[1], json_decode((string) file_get_contents('php://input'), true) ?: $_POST),

        $method === 'POST' && $uri === '/api/v1/finance/entries'
            => $apiController->createFinanceEntry(json_decode((string) file_get_contents('php://input'), true) ?: $_POST),

        $method === 'GET' && preg_match('#^/api/v1/events/(\d+)/report$#', $uri, $m) === 1
            => $apiController->eventReport((int) $m[1]),

        $method === 'GET' && $uri === '/api/v1/events'
            => $apiController->listEvents(),

        $method === 'GET' && $uri === '/api/v1/events/calendar'
            => $apiController->calendarEvents(),

        $method === 'POST' && $uri === '/api/v1/events'
            => $apiController->createEvent(json_decode((string) file_get_contents('php://input'), true) ?: $_POST),

        $method === 'GET' && preg_match('#^/api/v1/events/(\d+)/details$#', $uri, $m) === 1
            => $apiController->eventDetails((int) $m[1]),

        $method === 'POST' && preg_match('#^/api/v1/events/(\d+)/communicate$#', $uri, $m) === 1
            => $apiController->sendEventCommunication((int) $m[1], json_decode((string) file_get_contents('php://input'), true) ?: $_POST),

        $method === 'POST' && preg_match('#^/api/v1/events/(\d+)/budget-items$#', $uri, $m) === 1
            => $apiController->createEventBudgetItem((int) $m[1], json_decode((string) file_get_contents('php://input'), true) ?: $_POST),

        $method === 'PUT' && preg_match('#^/api/v1/events/(\d+)/budget-items/(\d+)$#', $uri, $m) === 1
            => $apiController->updateEventBudgetItem((int) $m[1], (int) $m[2], json_decode((string) file_get_contents('php://input'), true) ?: []),

        $method === 'POST' && preg_match('#^/api/v1/events/(\d+)/budget-items/(\d+)/post-finance$#', $uri, $m) === 1
            => $apiController->postEventBudgetItemToFinance((int) $m[1], (int) $m[2], json_decode((string) file_get_contents('php://input'), true) ?: $_POST),

        $method === 'POST' && preg_match('#^/api/v1/events/(\d+)/send-budget$#', $uri, $m) === 1
            => $apiController->sendEventBudgetToFinance((int) $m[1]),

        $method === 'POST' && preg_match('#^/api/v1/events/(\d+)/attendance/register$#', $uri, $m) === 1
            => $apiController->registerEventParticipant((int) $m[1], json_decode((string) file_get_contents('php://input'), true) ?: $_POST),

        $method === 'PUT' && preg_match('#^/api/v1/events/(\d+)/attendance/(\d+)$#', $uri, $m) === 1
            => $apiController->updateEventParticipantAttendance((int) $m[1], (int) $m[2], json_decode((string) file_get_contents('php://input'), true) ?: []),

        $method === 'GET' && $uri === '/api/v1/meta/groups'
            => $apiController->listGroups(),

        $method === 'GET' && $uri === '/api/v1/meta/users'
            => $apiController->listUsers(),

        $method === 'GET' && $uri === '/api/v1/finance/entries'
            => $apiController->listFinanceEntries(),

        $method === 'GET' && $uri === '/api/v1/finance/categories'
            => $apiController->listFinanceCategories(),

        /* ── Finance Module ── */
        $method === 'GET' && $uri === '/api/v1/finance/overview'
            => $apiController->financeOverview(),

        $method === 'GET' && $uri === '/api/v1/finance/entries/filtered'
            => $apiController->financeEntries(),

        $method === 'PUT' && preg_match('#^/api/v1/finance/entries/(\d+)/approve$#', $uri, $m) === 1
            => $apiController->approveFinanceEntry((int) $m[1], json_decode((string) file_get_contents('php://input'), true) ?: []),

        $method === 'GET' && $uri === '/api/v1/finance/pledges'
            => $apiController->listPledges(),

        $method === 'GET' && $uri === '/api/v1/finance/pledges/stats'
            => $apiController->pledgeStats(),

        $method === 'POST' && $uri === '/api/v1/finance/pledges'
            => $apiController->createPledge(json_decode((string) file_get_contents('php://input'), true) ?: $_POST),

        $method === 'GET' && $uri === '/api/v1/finance/budgets'
            => $apiController->listBudgets(),

        $method === 'POST' && $uri === '/api/v1/finance/budgets'
            => $apiController->createBudget(json_decode((string) file_get_contents('php://input'), true) ?: $_POST),

        $method === 'PUT' && preg_match('#^/api/v1/finance/budgets/(\d+)/approve$#', $uri, $m) === 1
            => $apiController->approveBudget((int) $m[1], json_decode((string) file_get_contents('php://input'), true) ?: []),

        $method === 'POST' && preg_match('#^/api/v1/finance/budgets/(\d+)/actual$#', $uri, $m) === 1
            => $apiController->addBudgetActualExpenses((int) $m[1], json_decode((string) file_get_contents('php://input'), true) ?: []),

        $method === 'POST' && preg_match('#^/api/v1/finance/budgets/(\d+)/close$#', $uri, $m) === 1
            => $apiController->closeBudget((int) $m[1], json_decode((string) file_get_contents('php://input'), true) ?: []),

        $method === 'GET' && preg_match('#^/api/v1/finance/budgets/(\d+)/expenses$#', $uri, $m) === 1
            => $apiController->listBudgetExpenses((int) $m[1]),

        $method === 'POST' && preg_match('#^/api/v1/finance/budgets/(\d+)/expenses$#', $uri, $m) === 1
            => $apiController->addBudgetExpense((int) $m[1], json_decode((string) file_get_contents('php://input'), true) ?: []),

        $method === 'DELETE' && preg_match('#^/api/v1/finance/budgets/(\d+)/expenses/(\d+)$#', $uri, $m) === 1
            => $apiController->deleteBudgetExpense((int) $m[1], (int) $m[2]),

        $method === 'GET' && $uri === '/api/v1/departments'
            => $apiController->listDepartments(),

        $method === 'POST' && $uri === '/api/v1/departments'
            => $apiController->createDepartment(json_decode((string) file_get_contents('php://input'), true) ?: $_POST),

        $method === 'PUT' && preg_match('#^/api/v1/departments/(\d+)$#', $uri, $m) === 1
            => $apiController->updateDepartment((int) $m[1], json_decode((string) file_get_contents('php://input'), true) ?: []),

        $method === 'DELETE' && preg_match('#^/api/v1/departments/(\d+)$#', $uri, $m) === 1
            => $apiController->deleteDepartment((int) $m[1]),

        /* ── Procurement Module ── */
        $method === 'GET' && $uri === '/api/v1/procurement/requests'
            => $apiController->listPurchaseRequests(),

        $method === 'POST' && $uri === '/api/v1/procurement/requests'
            => $apiController->createPurchaseRequest(json_decode((string) file_get_contents('php://input'), true) ?: []),

        $method === 'GET' && preg_match('#^/api/v1/procurement/requests/(\d+)$#', $uri, $m) === 1
            => $apiController->getPurchaseRequestDetail((int) $m[1]),

        $method === 'POST' && preg_match('#^/api/v1/procurement/requests/(\d+)/approve$#', $uri, $m) === 1
            => $apiController->approvePurchaseRequest((int) $m[1], json_decode((string) file_get_contents('php://input'), true) ?: []),

        $method === 'POST' && preg_match('#^/api/v1/procurement/requests/(\d+)/purchase$#', $uri, $m) === 1
            => $apiController->markPurchased((int) $m[1], json_decode((string) file_get_contents('php://input'), true) ?: []),

        $method === 'POST' && preg_match('#^/api/v1/procurement/requests/(\d+)/complete$#', $uri, $m) === 1
            => $apiController->completePurchaseRequest((int) $m[1]),

        $method === 'POST' && preg_match('#^/api/v1/procurement/requests/(\d+)/cancel$#', $uri, $m) === 1
            => $apiController->cancelPurchaseRequest((int) $m[1], json_decode((string) file_get_contents('php://input'), true) ?: []),

        $method === 'GET' && $uri === '/api/v1/procurement/active-budgets'
            => $apiController->listActiveBudgetsForProcurement(),

        /* ── Settings: Approval Workflows ── */
        $method === 'GET' && $uri === '/api/v1/settings/approval-workflows'
            => $apiController->listApprovalWorkflows(),

        $method === 'POST' && $uri === '/api/v1/settings/approval-workflows'
            => $apiController->saveApprovalWorkflow(json_decode((string) file_get_contents('php://input'), true) ?: []),

        $method === 'DELETE' && preg_match('#^/api/v1/settings/approval-workflows/(\d+)$#', $uri, $m) === 1
            => $apiController->deleteApprovalWorkflow((int) $m[1]),

        $method === 'GET' && $uri === '/api/v1/settings/roles'
            => $apiController->listRolesWithPermissions(),

        $method === 'GET' && $uri === '/api/v1/settings/permissions'
            => $apiController->listPermissions(),

        $method === 'POST' && preg_match('#^/api/v1/settings/roles/(\d+)/permissions$#', $uri, $m) === 1
            => $apiController->updateRolePermissions((int) $m[1], json_decode((string) file_get_contents('php://input'), true) ?: []),

        /* ── Settings: Users CRUD ── */
        $method === 'GET' && $uri === '/api/v1/settings/users'
            => $apiController->listAllUsers(),

        $method === 'POST' && $uri === '/api/v1/settings/users'
            => $apiController->createUser(json_decode((string) file_get_contents('php://input'), true) ?: []),

        $method === 'PUT' && preg_match('#^/api/v1/settings/users/(\d+)$#', $uri, $m) === 1
            => $apiController->updateUser((int) $m[1], json_decode((string) file_get_contents('php://input'), true) ?: []),

        $method === 'DELETE' && preg_match('#^/api/v1/settings/users/(\d+)$#', $uri, $m) === 1
            => $apiController->deleteUser((int) $m[1]),

        /* ── Settings: Church Profile ── */
        $method === 'GET' && $uri === '/api/v1/settings/church-profile'
            => $apiController->getChurchProfile(),

        $method === 'PUT' && $uri === '/api/v1/settings/church-profile'
            => $apiController->updateChurchProfile(json_decode((string) file_get_contents('php://input'), true) ?: []),

        $method === 'POST' && $uri === '/api/v1/settings/church-logo'
            => $apiController->uploadChurchLogo(),

        $method === 'DELETE' && $uri === '/api/v1/settings/church-logo'
            => $apiController->deleteChurchLogo(),

        /* ── Communication / Messaging ── */
        $method === 'POST' && $uri === '/api/v1/messages/send'
            => $apiController->sendMessage(json_decode((string) file_get_contents('php://input'), true) ?: []),

        $method === 'GET' && $uri === '/api/v1/messages'
            => $apiController->listMessages(),

        $method === 'GET' && preg_match('#^/api/v1/messages/(\d+)$#', $uri, $m) === 1
            => $apiController->getMessageDetail((int) $m[1]),

        $method === 'GET' && $uri === '/api/v1/messages/recipients/members'
            => $apiController->listMembersForMessaging(),

        $method === 'GET' && $uri === '/api/v1/messages/recipients/groups'
            => $apiController->listGroupsForMessaging(),

        /* ── Unified Reports ── */
        $method === 'GET' && $uri === '/api/v1/reports/budget-procurement'
            => $apiController->budgetProcurementReport(),

        $method === 'GET' && $uri === '/api/v1/reports/dashboard'
            => $apiController->reportsDashboard(),

        $method === 'GET' && $uri === '/api/v1/reports/export/csv'
            => $apiController->exportReportCsv(),

        /* ── Approval History ── */
        $method === 'GET' && preg_match('#^/api/v1/approvals/(\w+)/(\d+)$#', $uri, $m) === 1
            => $apiController->getApprovalHistory($m[1], (int) $m[2]),

        $method === 'GET' && preg_match('#^/api/v1/members/(\d+)/contributions$#', $uri, $m) === 1
            => $apiController->memberContributions((int) $m[1]),

        default => $apiController->notFound(),
    };
    exit;
}

// ────── Web routes ──────

// POST: login
if ($method === 'POST' && $uri === '/login') {
    // CSRF validation
    $csrfToken = (string) ($_POST['_csrf_token'] ?? '');
    if (!Auth::validateCsrfToken($csrfToken)) {
        $pageController->loginPage('Invalid security token. Please try again.');
        exit;
    }

    $email    = trim((string) ($_POST['email'] ?? ''));
    $phone    = trim((string) ($_POST['phone'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    // Determine identifier (email primary, phone fallback)
    $identifier = $email !== '' ? $email : $phone;
    if ($identifier === '' || $password === '') {
        $pageController->loginPage('Email and password are required.');
        exit;
    }

    // Brute-force check
    $bruteCheck = Auth::checkLoginAllowed($pdo, $identifier);
    if (!$bruteCheck['allowed']) {
        $pageController->loginPage('Too many login attempts. Please try again in ' . ceil($bruteCheck['retry_after'] / 60) . ' minute(s).');
        exit;
    }

    // Look up by email or phone — strict validation per mode
    if ($email !== '') {
        // Email mode: must be a valid email address
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $pageController->loginPage('Please enter a valid email address.');
            exit;
        }
        $stmt = $pdo->prepare(
            'SELECT u.id, u.full_name, u.password_hash, u.role_id, r.name AS role_name
             FROM users u INNER JOIN roles r ON r.id = u.role_id
             WHERE u.email = :email AND u.is_active = 1 LIMIT 1'
        );
        $stmt->execute([':email' => $email]);
    } elseif ($phone !== '') {
        // Phone mode: must contain only digits, +, spaces, dashes
        if (!preg_match('/^[+0-9\s\-]+$/', $phone)) {
            $pageController->loginPage('Please enter a valid phone number.');
            exit;
        }
        $stmt = $pdo->prepare(
            'SELECT u.id, u.full_name, u.password_hash, u.role_id, r.name AS role_name
             FROM users u INNER JOIN roles r ON r.id = u.role_id
             WHERE u.phone = :phone AND u.is_active = 1 LIMIT 1'
        );
        $stmt->execute([':phone' => $phone]);
    } else {
        $pageController->loginPage('Email or phone number is required.');
        exit;
    }
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password_hash'])) {
        Auth::recordLoginAttempt($pdo, $identifier);
        $remaining = $bruteCheck['remaining'] - 1;
        $msg = 'Invalid email or password.';
        if ($remaining <= 2 && $remaining > 0) {
            $msg .= " {$remaining} attempt(s) remaining.";
        }
        $pageController->loginPage($msg);
        exit;
    }

    // Success — clear attempts and update last login
    Auth::clearLoginAttempts($pdo, $identifier);
    $pdo->prepare('UPDATE users SET last_login_at = NOW() WHERE id = :id')->execute([':id' => $user['id']]);

    // Load permissions for this role
    try {
        $permStmt = $pdo->prepare(
            'SELECT p.name FROM permissions p
             INNER JOIN role_permissions rp ON rp.permission_id = p.id
             WHERE rp.role_id = :rid'
        );
        $permStmt->execute([':rid' => (int) $user['role_id']]);
        $user['permissions'] = $permStmt->fetchAll(PDO::FETCH_COLUMN);
    } catch (Throwable $e) {
        $user['permissions'] = [];
    }

    Auth::login($user);
    Response::redirect('/');
    exit;
}

// POST: logout
if ($method === 'POST' && $uri === '/logout') {
    Auth::logout();
    Response::redirect('/login');
    exit;
}

// ────── Forgot / Reset Password (public pages) ──────
if ($method === 'GET' && $uri === '/forgot-password') {
    $pageController->forgotPasswordPage();
    exit;
}
if ($method === 'POST' && $uri === '/forgot-password') {
    $csrfToken = (string) ($_POST['_csrf_token'] ?? '');
    if (!Auth::validateCsrfToken($csrfToken)) {
        $pageController->forgotPasswordPage('Invalid security token. Please try again.');
        exit;
    }
    $email = trim((string) ($_POST['email'] ?? ''));
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $pageController->forgotPasswordPage('A valid email address is required.');
        exit;
    }
    // Look up user by email
    $stmt = $pdo->prepare('SELECT id, full_name FROM users WHERE email = :email AND is_active = 1 LIMIT 1');
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();
    if (!$user) {
        // Generic message to avoid user enumeration
        $pageController->forgotPasswordPage(null, 'If an account exists with that email, a reset link has been sent.');
        exit;
    }
    // Generate secure token (no 6-digit code)
    $token = bin2hex(random_bytes(32));
    $expiresAt = date('Y-m-d H:i:s', strtotime('+15 minutes'));
    // Remove any old tokens for this user
    $pdo->prepare('DELETE FROM password_reset_tokens WHERE user_id = :uid')->execute([':uid' => $user['id']]);
    // Insert new token
    $pdo->prepare(
        'INSERT INTO password_reset_tokens (user_id, token, code_hash, expires_at, created_at)
         VALUES (:uid, :tok, :hash, :exp, NOW())'
    )->execute([':uid' => $user['id'], ':tok' => $token, ':hash' => '', ':exp' => $expiresAt]);

    // In production, send reset link via email. For now, redirect to reset page.
    Response::redirect('/reset-password?token=' . urlencode($token));
    exit;
}

if ($method === 'GET' && $uri === '/reset-password') {
    $token = trim((string) ($_GET['token'] ?? ''));
    if ($token === '') { Response::redirect('/login'); exit; }
    $pageController->resetPasswordPage($token);
    exit;
}
if ($method === 'POST' && $uri === '/reset-password') {
    $csrfToken = (string) ($_POST['_csrf_token'] ?? '');
    if (!Auth::validateCsrfToken($csrfToken)) {
        $pageController->resetPasswordPage((string) ($_POST['token'] ?? ''), 'Invalid security token. Please try again.');
        exit;
    }
    $token   = trim((string) ($_POST['token'] ?? ''));
    $newPass = (string) ($_POST['password'] ?? '');
    $confirm = (string) ($_POST['password_confirm'] ?? '');

    if ($token === '' || $newPass === '') {
        $pageController->resetPasswordPage($token, 'All fields are required.');
        exit;
    }
    if ($newPass !== $confirm) {
        $pageController->resetPasswordPage($token, 'Passwords do not match.');
        exit;
    }
    if (mb_strlen($newPass) < 8) {
        $pageController->resetPasswordPage($token, 'Password must be at least 8 characters.');
        exit;
    }
    // Validate token
    $stmt = $pdo->prepare(
        'SELECT prt.id, prt.user_id, prt.expires_at
         FROM password_reset_tokens prt
         WHERE prt.token = :tok LIMIT 1'
    );
    $stmt->execute([':tok' => $token]);
    $resetRow = $stmt->fetch();
    if (!$resetRow || strtotime($resetRow['expires_at']) < time()) {
        $pdo->prepare('DELETE FROM password_reset_tokens WHERE token = :tok')->execute([':tok' => $token]);
        $pageController->resetPasswordPage('', 'Reset link has expired. Please request a new one.');
        exit;
    }
    // Update password
    $hash = password_hash($newPass, PASSWORD_DEFAULT);
    $pdo->prepare('UPDATE users SET password_hash = :hash, updated_at = NOW() WHERE id = :uid')
        ->execute([':hash' => $hash, ':uid' => $resetRow['user_id']]);
    // Delete used token
    $pdo->prepare('DELETE FROM password_reset_tokens WHERE id = :id')->execute([':id' => $resetRow['id']]);

    $pageController->loginPage('Password reset successfully. Please sign in.');
    exit;
}

// Auth guard for all other web pages
$publicPages = ['/login', '/forgot-password', '/reset-password'];
if (!in_array($uri, $publicPages, true) && !Auth::check()) {
    Response::redirect('/login');
    exit;
}

// GET: login page
if ($method === 'GET' && $uri === '/login') {
    if (Auth::check()) {
        Response::redirect('/');
        exit;
    }
    $pageController->loginPage();
    exit;
}

// GET: dashboard
if ($method === 'GET' && $uri === '/') {
    $pageController->dashboard();
    exit;
}

// GET: assets module page (separate slug to avoid collision with static /assets folder)
if ($method === 'GET' && $uri === '/asset-center') {
    $pageController->module('assets');
    exit;
}

// GET: module pages
$webModules = ['members', 'events', 'attendance', 'finance', 'procurement', 'assets', 'communication', 'reports', 'settings'];
$trimmed = ltrim($uri, '/');
if ($method === 'GET' && in_array($trimmed, $webModules, true)) {
    $pageController->module($trimmed);
    exit;
}

if ($method === 'GET' && preg_match('#^/events/(\d+)$#', $uri, $m) === 1) {
    $pageController->eventDetails((int) $m[1]);
    exit;
}

// 404 fallback
http_response_code(404);
$pageController->module('404');
exit;
