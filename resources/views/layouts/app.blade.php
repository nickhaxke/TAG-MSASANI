<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Church Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f6f7f4;
            --panel: #ffffff;
            --primary: #0e7490;
            --accent: #f59e0b;
            --ink: #0f172a;
            --muted: #475569;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at 10% 10%, rgba(14, 116, 144, 0.18), transparent 30%),
                radial-gradient(circle at 85% 15%, rgba(245, 158, 11, 0.18), transparent 35%),
                radial-gradient(circle at 55% 100%, rgba(15, 23, 42, 0.08), transparent 40%),
                var(--bg);
        }

        .glass {
            background: rgba(255, 255, 255, 0.82);
            backdrop-filter: blur(8px);
        }

        .page-enter {
            animation: fadeSlide 420ms ease-out;
        }

        @keyframes fadeSlide {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="min-h-screen md:grid md:grid-cols-[260px_1fr]">
        <aside class="glass border-b md:border-b-0 md:border-r border-slate-200/80 p-4 md:p-6">
            <div class="flex items-center justify-between md:block">
                <h1 class="text-lg md:text-xl font-extrabold tracking-tight">Kanisa CMS</h1>
                <p class="hidden md:block mt-1 text-sm text-slate-600">Tanzania Church Operations</p>
            </div>
            <nav class="mt-4 md:mt-8 grid grid-cols-2 md:grid-cols-1 gap-2 text-sm">
                <a href="/" class="rounded-xl px-3 py-2 hover:bg-cyan-50">Dashboard</a>
                <a href="/members" class="rounded-xl px-3 py-2 hover:bg-cyan-50">Members</a>
                <a href="/events" class="rounded-xl px-3 py-2 hover:bg-cyan-50">Events</a>
                <a href="/finance" class="rounded-xl px-3 py-2 hover:bg-cyan-50">Finance</a>
                <a href="/procurement" class="rounded-xl px-3 py-2 hover:bg-cyan-50">Procurement</a>
                <a href="/assets" class="rounded-xl px-3 py-2 hover:bg-cyan-50">Assets</a>
                <a href="/communication" class="rounded-xl px-3 py-2 hover:bg-cyan-50">Communication</a>
                <a href="/reports" class="rounded-xl px-3 py-2 hover:bg-cyan-50">Reports</a>
            </nav>
        </aside>

        <main class="p-4 md:p-8 page-enter">
            @yield('content')
        </main>
    </div>
</body>
</html>
