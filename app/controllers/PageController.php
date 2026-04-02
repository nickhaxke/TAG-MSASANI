<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Response;
use PDO;

final class PageController
{
    public function __construct(private PDO $pdo)
    {
    }

    public function loginPage(?string $error = null): void
    {
        $brand = Response::loadChurchBranding();
        Response::view('pages/login.php', [
            'title' => $brand['church_name'] . ' Login',
            'page'  => 'login',
            'error' => $error,
        ]);
    }

    public function dashboard(): void
    {
        $members = (int) $this->pdo->query("SELECT COUNT(*) FROM members WHERE member_status = 'active'")->fetchColumn();
        $income  = (float) $this->pdo->query(
            "SELECT COALESCE(SUM(fe.amount),0) FROM finance_entries fe
             INNER JOIN finance_categories fc ON fc.id = fe.category_id
             WHERE fc.category_type='income' AND DATE_FORMAT(fe.entry_date,'%Y-%m') = DATE_FORMAT(CURRENT_DATE,'%Y-%m')"
        )->fetchColumn();
        $expenses = (float) $this->pdo->query(
            "SELECT COALESCE(SUM(fe.amount),0) FROM finance_entries fe
             INNER JOIN finance_categories fc ON fc.id = fe.category_id
             WHERE fc.category_type='expense' AND DATE_FORMAT(fe.entry_date,'%Y-%m') = DATE_FORMAT(CURRENT_DATE,'%Y-%m')"
        )->fetchColumn();
        $events = (int) $this->pdo->query("SELECT COUNT(*) FROM `events` WHERE start_datetime >= NOW() AND status IN ('planned','ongoing')")->fetchColumn();
        $groups = (int) $this->pdo->query("SELECT COUNT(*) FROM `groups`")->fetchColumn();
        $themeVerse = $this->resolveThemeVerse();

        $brand = Response::loadChurchBranding();
        Response::view('pages/dashboard.php', [
            'title' => $brand['church_name'] . ' Dashboard',
            'page'  => 'dashboard',
            'stats' => compact('members', 'income', 'expenses', 'events', 'groups'),
            'themeVerse' => $themeVerse,
        ]);
    }

    public function eventDetails(int $eventId): void
    {
        Response::view('pages/event_details.php', [
            'title' => 'Event Details',
            'page' => 'events',
            'eventId' => $eventId,
            'themeVerse' => $this->resolveThemeVerse(),
        ]);
    }

    public function module(string $module): void
    {
        $allowed = ['members', 'events', 'attendance', 'finance', 'procurement', 'assets', 'communication', 'reports', 'settings'];
        if (!in_array($module, $allowed, true)) {
            Response::view('pages/404.php', ['title' => 'Not Found', 'page' => '404']);
            return;
        }

        $titles = [
            'members'       => 'Members',
            'events'        => 'Events',
            'attendance'    => 'Attendance',
            'finance'       => 'Finance',
            'procurement'   => 'Procurement',
            'assets'        => 'Assets',
            'communication' => 'Communication',
            'reports'       => 'Reports',
            'settings'      => 'Settings',
        ];

        Response::view('pages/' . $module . '.php', [
            'title' => $titles[$module] ?? ucfirst($module),
            'page'  => $module,
            'themeVerse' => $this->resolveThemeVerse(),
        ]);
    }

    private function resolveThemeVerse(): array
    {
        $themeVerse = [
            'reference' => '1 Wakorintho 14:40',
            'verse' => 'Mambo yote na yatendeke kwa uzuri na kwa utaratibu.',
        ];

        try {
            $verseStmt = $this->pdo->query(
                "SELECT verse_reference, verse_text
                 FROM theme_verses
                 WHERE is_active = 1
                   AND (start_date IS NULL OR start_date <= CURRENT_DATE)
                   AND (end_date IS NULL OR end_date >= CURRENT_DATE)
                 ORDER BY RAND()
                 LIMIT 1"
            );
            $row = $verseStmt ? $verseStmt->fetch() : false;
            if ($row && !empty($row['verse_text'])) {
                $themeVerse = [
                    'reference' => (string) ($row['verse_reference'] ?? ''),
                    'verse' => (string) $row['verse_text'],
                ];
            }
        } catch (\Throwable $e) {
            // Keep fallback verse when migration has not yet been applied.
        }

        return $themeVerse;
    }

    public function forgotPasswordPage(?string $error = null, ?string $success = null): void
    {
        Response::view('pages/forgot_password.php', [
            'title'   => 'Forgot Password',
            'page'    => 'login',
            'error'   => $error,
            'success' => $success,
        ]);
    }

    public function resetPasswordPage(string $token = '', ?string $error = null): void
    {
        Response::view('pages/reset_password.php', [
            'title' => 'Reset Password',
            'page'  => 'login',
            'token' => $token,
            'error' => $error,
        ]);
    }
}
