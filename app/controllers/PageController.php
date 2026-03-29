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
        Response::view('pages/login.php', [
            'title' => 'TAG MSASANI Login',
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

        Response::view('pages/dashboard.php', [
            'title' => 'TAG MSASANI Dashboard',
            'page'  => 'dashboard',
            'stats' => compact('members', 'income', 'expenses', 'events', 'groups'),
        ]);
    }

    public function eventDetails(int $eventId): void
    {
        Response::view('pages/event_details.php', [
            'title' => 'Event Details',
            'page' => 'events',
            'eventId' => $eventId,
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
        ]);
    }
}
