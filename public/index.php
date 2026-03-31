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

    if (!Auth::check() && $uri !== '/api/v1/auth/login') {
        Response::json(['success' => false, 'message' => 'Unauthenticated'], 401);
    }

    match (true) {
        $method === 'POST' && $uri === '/api/v1/auth/login'
            => $apiController->login(json_decode((string) file_get_contents('php://input'), true) ?: $_POST),

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

        $method === 'GET' && preg_match('#^/api/v1/members/(\d+)/contributions$#', $uri, $m) === 1
            => $apiController->memberContributions((int) $m[1]),

        default => $apiController->notFound(),
    };
    exit;
}

// ────── Web routes ──────

// POST: login
if ($method === 'POST' && $uri === '/login') {
    $phone    = trim((string) ($_POST['phone'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if ($phone === '' || $password === '') {
        $pageController->loginPage('Phone and password are required.');
        exit;
    }

    $stmt = $pdo->prepare(
        'SELECT u.id, u.full_name, u.password_hash, r.name AS role_name
         FROM users u INNER JOIN roles r ON r.id = u.role_id
         WHERE u.phone = :phone AND u.is_active = 1 LIMIT 1'
    );
    $stmt->execute([':phone' => $phone]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password_hash'])) {
        $pageController->loginPage('Invalid phone or password.');
        exit;
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

// Auth guard for all other web pages
if ($uri !== '/login' && !Auth::check()) {
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
