<?php
/** @var string $title */
/** @var string $page */
/** @var string $viewPath */
/** @var string $baseUrl */

$user = $_SESSION['user'] ?? null;
$B = $baseUrl; // shorthand for links
$themeVerseRef = trim((string)($themeVerse['reference'] ?? '1 Wakorintho 14:40'));
$themeVerseText = trim((string)($themeVerse['verse'] ?? 'Mambo yote na yatendeke kwa uzuri na kwa utaratibu.'));

$menu = [
    'dashboard'     => ['label' => 'Dashboard',     'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'href' => '/'],
    'events'        => ['label' => 'Events',        'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'href' => '/events'],
    'members'       => ['label' => 'Members',       'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'href' => '/members'],
    'attendance'    => ['label' => 'Attendance',    'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'href' => '/attendance'],
    'finance'       => ['label' => 'Finance',       'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'href' => '/finance'],
    'procurement'   => ['label' => 'Procurement',   'icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5', 'href' => '/procurement'],
    'assets'        => ['label' => 'Assets',        'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2', 'href' => '/asset-center'],
    'reports'       => ['label' => 'Reports',       'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586', 'href' => '/reports'],
    'settings'      => ['label' => 'Settings',      'icon' => 'M10.325 4.317a1 1 0 011.35-.936 1 1 0 011.35.936l.096.288a1 1 0 00.95.69h.303a1 1 0 01.987 1.157l-.056.337a1 1 0 00.287.885l.214.214a1 1 0 010 1.414l-.214.214a1 1 0 00-.287.885l.056.337a1 1 0 01-.987 1.157h-.303a1 1 0 00-.95.69l-.096.288a1 1 0 01-1.35.936 1 1 0 01-1.35-.936l-.096-.288a1 1 0 00-.95-.69h-.303a1 1 0 01-.987-1.157l.056-.337a1 1 0 00-.287-.885l-.214-.214a1 1 0 010-1.414l.214-.214a1 1 0 00.287-.885l-.056-.337A1 1 0 019.025 5.36h.303a1 1 0 00.95-.69l.047-.353z', 'href' => '/settings'],
];
?>
<!doctype html>
<html lang="en" class="h-full bg-mist-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title) ?> - <?= htmlspecialchars($churchName ?? 'Church CMS') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Nunito:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        royal:   { 50:'#f1f0ff', 100:'#e4e1ff', 200:'#cbc3ff', 300:'#a99bff', 400:'#8470ff', 500:'#6349f6', 600:'#4f36d8', 700:'#3b2aa8', 800:'#2c2277', 900:'#1c1847' },
                        dawn:    { 50:'#f0f7ff', 100:'#ddebff', 200:'#b9d8ff', 300:'#83bcff', 400:'#4f9cff', 500:'#2878f5', 600:'#1a5fd8', 700:'#174bab', 800:'#183f86', 900:'#1a376d' },
                        glory:   { 50:'#fff9e8', 100:'#ffefbf', 200:'#ffe18b', 300:'#ffd354', 400:'#ffc629', 500:'#f5ad0d', 600:'#d98506', 700:'#b06008', 800:'#904a10', 900:'#763d11' },
                        mist:    { 50:'#f8f8fb', 100:'#f2f2f8', 200:'#e6e7f0', 300:'#d4d6e4', 400:'#adb2ca', 500:'#8d94b3', 600:'#737b9d', 700:'#606884', 800:'#53586f', 900:'#494c5f' },
                    },
                    fontFamily: {
                        heading: ['Cormorant Garamond', 'serif'],
                        body: ['Nunito', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="<?= $B ?>/assets/css/app.css">
    <meta name="csrf-token" content="<?= htmlspecialchars(\App\Core\Auth::getCsrfToken()) ?>">
    <script>const BASE_URL = '<?= $B ?>';</script>
    <script>
    /* ── Global CSRF for all fetch() calls ── */
    const CSRF_TOKEN = document.currentScript.parentElement.querySelector('meta[name="csrf-token"]')?.content || '';
    (function(){
        const _origFetch = window.fetch;
        window.fetch = function(url, opts) {
            opts = opts || {};
            if (opts.method && opts.method.toUpperCase() !== 'GET') {
                if (opts.headers instanceof Headers) {
                    if (!opts.headers.has('X-CSRF-TOKEN')) opts.headers.set('X-CSRF-TOKEN', CSRF_TOKEN);
                } else {
                    opts.headers = Object.assign({'X-CSRF-TOKEN': CSRF_TOKEN}, opts.headers || {});
                }
            }
            return _origFetch.call(this, url, opts);
        };
    })();
    </script>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Nunito', sans-serif; margin: 0; padding: 0; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Cormorant Garamond', serif; }
    </style>
</head>

<?php if ($page === 'login' || $page === 'forgot_password' || $page === 'reset_password'): ?>
<?php require __DIR__ . '/../' . $viewPath; ?>
<?php return; endif; ?>

<body class="h-full font-body text-mist-900 bg-mist-50">
<div class="min-h-full flex">

    <aside id="sidebar" class="fixed inset-y-0 left-0 z-30 w-72 bg-gradient-to-b from-royal-900 via-royal-800 to-dawn-900 transform -translate-x-full lg:translate-x-0 transition-transform duration-200 ease-in-out flex flex-col shadow-2xl shadow-royal-900/40">
        <div class="flex items-center gap-3 px-5 py-6 border-b border-white/10">
            <?php if (!empty($churchLogo)): ?>
                <img src="<?= htmlspecialchars($baseUrl . $churchLogo) ?>" alt="<?= htmlspecialchars($churchName ?? '') ?>" class="w-11 h-11 rounded-2xl object-cover shadow-md">
            <?php else: ?>
            <div class="w-11 h-11 rounded-2xl bg-glory-500/90 text-royal-900 flex items-center justify-center shadow-md">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18M8 7h8M9 13h6"/>
                </svg>
            </div>
            <?php endif; ?>
            <div>
                <h1 class="text-white font-heading font-semibold text-2xl leading-tight"><?= htmlspecialchars($churchName ?? 'Church CMS') ?></h1>
                <p class="text-white/70 text-xs tracking-wide">Church Management System</p>
            </div>
        </div>

        <div class="px-5 py-4">
            <div class="rounded-2xl bg-white/10 border border-white/15 px-4 py-3 text-white/90 text-sm">
                <p class="text-xs uppercase tracking-widest text-glory-200">Theme Verse</p>
                <p class="mt-1 italic">"<?= htmlspecialchars($themeVerseText, ENT_QUOTES, 'UTF-8') ?>"</p>
                <?php if ($themeVerseRef !== ''): ?>
                    <p class="mt-1 text-[11px] text-glory-100 font-semibold"><?= htmlspecialchars($themeVerseRef, ENT_QUOTES, 'UTF-8') ?></p>
                <?php endif; ?>
            </div>
        </div>

        <nav class="flex-1 px-3 pb-4 space-y-1 overflow-y-auto">
            <?php foreach ($menu as $key => $item): ?>
                <a href="<?= $B . $item['href'] ?>"
                   class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150
                          <?= $page === $key
                              ? 'bg-white/20 text-white shadow-md shadow-black/20'
                              : 'text-white/80 hover:bg-white/10 hover:text-white' ?>">
                    <svg class="w-5 h-5 flex-shrink-0 <?= $page === $key ? 'text-glory-300' : 'text-white/50 group-hover:text-glory-300' ?>"
                         fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="<?= $item['icon'] ?>"/>
                    </svg>
                    <?= $item['label'] ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <?php if ($user): ?>
        <div class="px-4 py-4 border-t border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-glory-400 text-royal-900 flex items-center justify-center font-bold text-sm">
                    <?= strtoupper(substr($user['full_name'], 0, 1)) ?>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white text-sm font-medium truncate"><?= htmlspecialchars($user['full_name']) ?></p>
                    <p class="text-white/60 text-xs truncate"><?= htmlspecialchars($user['role']) ?></p>
                </div>
            </div>
            <form action="<?= $B ?>/logout" method="post" class="mt-3">
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-xl text-sm text-white/85 hover:bg-white/10 hover:text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Sign out
                </button>
            </form>
        </div>
        <?php endif; ?>
    </aside>

    <div id="sidebar-overlay" class="fixed inset-0 bg-gray-900/50 z-20 hidden lg:hidden" onclick="toggleSidebar()"></div>

    <div class="flex-1 lg:ml-72 flex flex-col min-h-screen">

        <header class="sticky top-0 z-10 bg-white/90 backdrop-blur border-b border-mist-200 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg text-mist-600 hover:bg-mist-100 hover:text-mist-800 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <h2 class="text-xl font-heading font-semibold text-royal-800 hidden sm:block"><?= htmlspecialchars($title) ?></h2>

                <div class="flex items-center gap-3">
                    <div class="hidden md:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-gradient-to-r from-royal-50 to-dawn-50 border border-royal-100">
                        <span class="w-2 h-2 rounded-full bg-glory-500"></span>
                        <span class="text-sm text-royal-800 font-semibold"><?= date('D, d M Y') ?></span>
                    </div>
                    <?php if ($user): ?>
                    <div class="w-8 h-8 rounded-full bg-royal-100 text-royal-700 flex items-center justify-center font-semibold text-sm">
                        <?= strtoupper(substr($user['full_name'], 0, 1)) ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6 lg:p-8 relative overflow-hidden" id="main-content">
            <div class="pointer-events-none absolute -top-40 -right-40 w-96 h-96 rounded-full bg-gradient-to-br from-dawn-200/50 to-royal-300/40 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-40 -left-40 w-96 h-96 rounded-full bg-gradient-to-br from-glory-200/40 to-royal-200/40 blur-3xl"></div>
            <div class="relative z-10 h-full">
            <?php require __DIR__ . '/../' . $viewPath; ?>
            </div>
        </main>

        <footer id="app-footer" class="border-t border-mist-200 px-6 py-3 text-center text-xs text-mist-500 bg-white/70">
            &copy; <?= date('Y') ?> <?= htmlspecialchars($churchName ?? 'Church CMS') ?> - Church Management Platform
        </footer>
    </div>
</div>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
}
</script>
</body>
</html>
