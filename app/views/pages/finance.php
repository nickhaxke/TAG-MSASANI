<?php $B = $baseUrl ?? ''; ?>

<div class="finance-module">

<!-- Finance Module Header -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-3xl font-heading font-semibold text-royal-900">Finance Center</h1>
        <p class="text-mist-600 text-sm mt-0.5">Overview, income, expenses, pledges, budgets, approvals, and reports</p>
    </div>
    <div class="flex items-center gap-3">
        <select id="fin-month-select" class="rounded-xl border border-mist-200 px-3 py-2 text-sm text-mist-700 focus:ring-2 focus:ring-royal-300">
        </select>
        <button onclick="openModal('entry-modal')"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-royal-600 hover:bg-royal-700 text-white font-semibold rounded-xl shadow-sm transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add Entry
        </button>
    </div>
</div>

<!-- ═══════════ TABS ═══════════ -->
<div class="mb-6 border-b border-mist-200 overflow-x-auto">
    <nav class="flex gap-1 -mb-px whitespace-nowrap" id="fin-tabs">
        <button data-tab="muhtasari" class="fin-tab fin-tab-active px-4 py-2.5 text-sm font-semibold border-b-2 transition-colors">Overview</button>
        <button data-tab="mapato" class="fin-tab px-4 py-2.5 text-sm font-semibold border-b-2 border-transparent text-mist-500 hover:text-royal-700 transition-colors">Income</button>
        <button data-tab="matumizi" class="fin-tab px-4 py-2.5 text-sm font-semibold border-b-2 border-transparent text-mist-500 hover:text-royal-700 transition-colors">Expenses</button>
        <button data-tab="ahadi" class="fin-tab px-4 py-2.5 text-sm font-semibold border-b-2 border-transparent text-mist-500 hover:text-royal-700 transition-colors">Pledges</button>
        <button data-tab="bajeti" class="fin-tab px-4 py-2.5 text-sm font-semibold border-b-2 border-transparent text-mist-500 hover:text-royal-700 transition-colors">Budgets</button>
        <button data-tab="idhinisho" class="fin-tab px-4 py-2.5 text-sm font-semibold border-b-2 border-transparent text-mist-500 hover:text-royal-700 transition-colors">Approvals <span id="approval-badge" class="hidden ml-1 px-1.5 py-0.5 text-xs bg-red-500 text-white rounded-full"></span></button>
        <button data-tab="ripoti" class="fin-tab px-4 py-2.5 text-sm font-semibold border-b-2 border-transparent text-mist-500 hover:text-royal-700 transition-colors">Reports</button>
    </nav>
</div>

<!-- ═══════════ TAB 1: MUHTASARI (Dashboard) ═══════════ -->
<div id="tab-muhtasari" class="fin-panel">

    <!-- ── KPI Cards ─────────────────────────────────────── -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        <!-- Income -->
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-2xl p-5 text-white shadow-md">
            <div class="flex items-start justify-between mb-3">
                <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                </div>
                <span class="text-[10px] font-semibold bg-white/20 rounded-full px-2 py-0.5" id="kpi-month-label">Month</span>
            </div>
            <p class="text-xl font-extrabold leading-tight" id="kpi-income">TZS 0</p>
            <p class="text-xs text-emerald-100 mt-1 font-medium">Total Income</p>
            <div class="mt-3 h-1 rounded-full bg-white/20"><div id="kpi-income-bar" class="h-1 rounded-full bg-white/70 transition-all duration-700" style="width:0%"></div></div>
        </div>

        <!-- Expenses -->
        <div class="bg-gradient-to-br from-red-500 to-rose-700 rounded-2xl p-5 text-white shadow-md">
            <div class="flex items-start justify-between mb-3">
                <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15"/></svg>
                </div>
                <span class="text-[10px] font-semibold bg-white/20 rounded-full px-2 py-0.5">Month</span>
            </div>
            <p class="text-xl font-extrabold leading-tight" id="kpi-expense">TZS 0</p>
            <p class="text-xs text-red-100 mt-1 font-medium">Total Expenses</p>
            <div class="mt-3 h-1 rounded-full bg-white/20"><div id="kpi-expense-bar" class="h-1 rounded-full bg-white/70 transition-all duration-700" style="width:0%"></div></div>
        </div>

        <!-- Balance -->
        <div class="bg-gradient-to-br from-royal-700 to-royal-900 rounded-2xl p-5 text-white shadow-md">
            <div class="flex items-start justify-between mb-3">
                <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </div>
                <span id="kpi-balance-label" class="text-[10px] font-semibold bg-white/20 rounded-full px-2 py-0.5">Balance</span>
            </div>
            <p class="text-xl font-extrabold leading-tight" id="kpi-balance">TZS 0</p>
            <p class="text-xs text-blue-200 mt-1 font-medium">Net Balance</p>
            <p class="text-[10px] text-blue-200 mt-2 font-medium" id="kpi-alltime">All-time: TZS 0</p>
        </div>

        <!-- Pending Actions -->
        <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl p-5 text-white shadow-md">
            <div class="flex items-start justify-between mb-3">
                <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <button onclick="switchToApprovals()" class="text-[10px] font-semibold bg-white/20 hover:bg-white/30 rounded-full px-2 py-0.5 transition">Review</button>
            </div>
            <p class="text-xl font-extrabold leading-tight" id="kpi-pending-total">0</p>
            <p class="text-xs text-amber-100 mt-1 font-medium">Needs Approval</p>
            <div class="mt-2 flex gap-2 text-[10px]">
                <span class="bg-white/20 rounded-full px-2 py-0.5">Entries: <b id="pending-entries-count">0</b></span>
                <span class="bg-white/20 rounded-full px-2 py-0.5">Budgets: <b id="pending-budgets-count">0</b></span>
            </div>
        </div>
    </div>

    <!-- ── Health Bar ─────────────────────────────────────── -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-6 py-4 mb-6">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-semibold text-gray-700">Monthly Financial Health</span>
            <span class="text-xs text-gray-400" id="health-label">—</span>
        </div>
        <div class="w-full h-3 rounded-full bg-gray-100 overflow-hidden">
            <div id="health-bar" class="h-3 rounded-full transition-all duration-1000 bg-emerald-500" style="width:0%"></div>
        </div>
        <div class="flex justify-between text-[10px] text-gray-400 mt-1.5">
            <span>Expense-heavy</span><span>Balanced</span><span>Income-surplus</span>
        </div>
    </div>

    <!-- ── Charts ─────────────────────────────────────── -->
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-5 mb-6">
        <div class="lg:col-span-3 bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-800">6-Month Trend</h3>
                <div class="flex items-center gap-3 text-xs text-gray-500">
                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span>Income</span>
                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-red-500 inline-block"></span>Expenses</span>
                </div>
            </div>
            <canvas id="trend-chart" height="190"></canvas>
        </div>
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-bold text-gray-800 mb-4">Category Breakdown</h3>
            <canvas id="category-chart" height="190"></canvas>
            <div id="category-legend" class="mt-3 space-y-1.5 max-h-28 overflow-y-auto"></div>
        </div>
    </div>

    <!-- ── Pledges + Recent Activity ─────────────────────────────────────── -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        <!-- Pledge Status -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-bold text-gray-800 mb-4">Pledge Status</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 rounded-xl bg-amber-50 border border-amber-100">
                    <div class="flex items-center gap-2.5">
                        <div class="w-2 h-2 rounded-full bg-amber-400"></div>
                        <span class="text-sm font-medium text-gray-700">Outstanding</span>
                    </div>
                    <span class="text-sm font-bold text-amber-700" id="kpi-pledges">TZS 0</span>
                </div>
                <div class="flex items-center justify-between p-3 rounded-xl bg-red-50 border border-red-100">
                    <div class="flex items-center gap-2.5">
                        <div class="w-2 h-2 rounded-full bg-red-400"></div>
                        <span class="text-sm font-medium text-gray-700">Entry Approvals</span>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700" id="pending-entries-count2">0</span>
                </div>
                <div class="flex items-center justify-between p-3 rounded-xl bg-purple-50 border border-purple-100">
                    <div class="flex items-center gap-2.5">
                        <div class="w-2 h-2 rounded-full bg-purple-400"></div>
                        <span class="text-sm font-medium text-gray-700">Budget Approvals</span>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-purple-100 text-purple-700" id="pending-budgets-count2">0</span>
                </div>
            </div>
            <button onclick="switchToApprovals()" class="mt-4 w-full py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold transition active:scale-95">
                Go to Approvals →
            </button>
        </div>

        <!-- Recent Activity -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-800">Recent Activity</h3>
                <span class="text-xs text-gray-400">Last 10 entries</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left">
                            <th class="pb-2.5 text-xs font-semibold text-gray-400 uppercase tracking-wide">Date</th>
                            <th class="pb-2.5 text-xs font-semibold text-gray-400 uppercase tracking-wide">Category</th>
                            <th class="pb-2.5 text-xs font-semibold text-gray-400 uppercase tracking-wide">Type</th>
                            <th class="pb-2.5 text-xs font-semibold text-gray-400 uppercase tracking-wide text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody id="recent-tbody" class="divide-y divide-gray-50"></tbody>
                </table>
            </div>
            <div id="recent-empty" class="hidden py-8 text-center text-sm text-gray-400">No recent entries</div>
        </div>
    </div>
</div>

<!-- ═══════════ TAB 2: MAPATO ═══════════ -->
<div id="tab-mapato" class="fin-panel hidden">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3">
            <h2 class="font-semibold text-gray-800">Income Records</h2>
            <div class="flex gap-2 flex-wrap">
                <select id="mapato-cat-filter" class="border border-gray-300 rounded-lg px-2 py-1.5 text-xs"><option value="">All Categories</option></select>
                <input type="date" id="mapato-from" class="border border-gray-300 rounded-lg px-2 py-1.5 text-xs">
                <input type="date" id="mapato-to" class="border border-gray-300 rounded-lg px-2 py-1.5 text-xs">
                <button onclick="loadIncome()" class="px-3 py-1.5 bg-green-600 text-white text-xs rounded-lg hover:bg-green-700 font-semibold">Search</button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Entry No</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Category</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Member</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Amount (TZS)</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Method</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Description</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Receipt</th>
                    </tr>
                </thead>
                <tbody id="mapato-tbody" class="divide-y divide-gray-100"></tbody>
            </table>
        </div>
        <div id="mapato-empty" class="hidden px-5 py-12 text-center text-gray-400">
            <p class="font-medium">No income records found</p>
        </div>
        <div class="px-5 py-3 border-t border-gray-100 flex justify-between items-center">
            <span id="mapato-count" class="text-sm text-gray-500">0 records</span>
            <span id="mapato-total" class="text-sm font-bold text-green-600">TZS 0</span>
        </div>
    </div>
</div>

<!-- ═══════════ TAB 3: MATUMIZI ═══════════ -->
<div id="tab-matumizi" class="fin-panel hidden">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3">
            <h2 class="font-semibold text-gray-800">Expense Records</h2>
            <div class="flex gap-2 flex-wrap">
                <select id="matumizi-cat-filter" class="border border-gray-300 rounded-lg px-2 py-1.5 text-xs"><option value="">All Categories</option></select>
                <select id="matumizi-source-filter" class="border border-gray-300 rounded-lg px-2 py-1.5 text-xs">
                    <option value="">All Sources</option>
                    <option value="manual">Manual</option>
                    <option value="procurement">Procurement</option>
                    <option value="event">Event</option>
                </select>
                <button onclick="loadExpenses()" class="px-3 py-1.5 bg-red-600 text-white text-xs rounded-lg hover:bg-red-700 font-semibold">Search</button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Entry No</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Category</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Source</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Amount (TZS)</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Method</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Description</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Receipt</th>
                    </tr>
                </thead>
                <tbody id="matumizi-tbody" class="divide-y divide-gray-100"></tbody>
            </table>
        </div>
        <div id="matumizi-empty" class="hidden px-5 py-12 text-center text-gray-400">
            <p class="font-medium">No expense records found</p>
        </div>
        <div class="px-5 py-3 border-t border-gray-100 flex justify-between items-center">
            <span id="matumizi-count" class="text-sm text-gray-500">0 records</span>
            <span id="matumizi-total" class="text-sm font-bold text-red-600">TZS 0</span>
        </div>
    </div>
</div>

<!-- ═══════════ TAB 4: AHADI (Pledges) ═══════════ -->
<div id="tab-ahadi" class="fin-panel hidden">
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 text-center">
            <p class="text-2xl font-bold text-gray-900" id="pledge-total-count">0</p>
            <p class="text-xs text-gray-500">Total Pledges</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 text-center">
            <p class="text-2xl font-bold text-green-600" id="pledge-total-pledged">TZS 0</p>
            <p class="text-xs text-gray-500">Total Pledged</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 text-center">
            <p class="text-2xl font-bold text-blue-600" id="pledge-total-paid">TZS 0</p>
            <p class="text-xs text-gray-500">Total Paid</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 text-center">
            <p class="text-2xl font-bold text-amber-600" id="pledge-total-balance">TZS 0</p>
            <p class="text-xs text-gray-500">Outstanding</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-800">Pledge List</h2>
            <button onclick="openModal('pledge-modal')" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-xl">+ Add Pledge</button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Pledge No</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Member</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Campaign</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Amount</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Paid</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Balance</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Due Date</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody id="ahadi-tbody" class="divide-y divide-gray-100"></tbody>
            </table>
        </div>
        <div id="ahadi-empty" class="hidden px-5 py-12 text-center text-gray-400">
            <p class="font-medium">No pledges recorded</p>
        </div>
    </div>
</div>

<!-- ═══════════ TAB 5: BAJETI ═══════════ -->
<div id="tab-bajeti" class="fin-panel hidden">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3">
            <h2 class="font-semibold text-gray-800">Department Budgets</h2>
            <div class="flex gap-2">
                <select id="bajeti-month-filter" class="border border-gray-300 rounded-lg px-2 py-1.5 text-xs"></select>
                <button onclick="openModal('budget-modal')" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-xl">+ Add Budget</button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Department</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Month</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Budget</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Spent</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Usage %</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Approved By</th>
                    </tr>
                </thead>
                <tbody id="bajeti-tbody" class="divide-y divide-gray-100"></tbody>
            </table>
        </div>
        <div id="bajeti-empty" class="hidden px-5 py-12 text-center text-gray-400">
            <p class="font-medium">No budgets prepared</p>
        </div>
    </div>
</div>

<!-- ═══════════ TAB 6: IDHINISHO (Approvals) ═══════════ -->
<div id="tab-idhinisho" class="fin-panel hidden">
    <!-- Summary bar -->
    <div class="flex flex-wrap items-center gap-3 mb-5">
        <h2 class="text-lg font-bold text-royal-800 flex-1">Approval Queue</h2>
        <span id="appr-entry-count-badge" class="hidden px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-semibold"></span>
        <span id="appr-budget-count-badge" class="hidden px-3 py-1 rounded-full bg-purple-100 text-purple-700 text-xs font-semibold"></span>
        <button onclick="loadApprovals()" class="px-3 py-1.5 rounded-xl bg-mist-100 hover:bg-mist-200 text-mist-700 text-xs font-semibold transition flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582M20 20v-5h-.581M5.635 19A9 9 0 104.582 9"/></svg>
            Refresh
        </button>
    </div>

    <!-- Finance Entries Pending -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="px-5 py-3.5 border-b border-gray-100 flex items-center gap-2">
            <div class="w-2 h-2 rounded-full bg-amber-400"></div>
            <h3 class="font-semibold text-gray-800 text-sm">Finance Entries — Pending Approval</h3>
        </div>
        <div id="appr-entries-loading" class="px-5 py-6 text-center text-sm text-gray-400">Loading…</div>
        <div id="appr-entries-wrap" class="hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Entry No</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Category</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Amount</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Source</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Description</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Recorded By</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="appr-entries-tbody" class="divide-y divide-gray-100"></tbody>
                </table>
            </div>
        </div>
        <div id="appr-entries-empty" class="hidden px-5 py-10 text-center">
            <div class="w-10 h-10 mx-auto rounded-full bg-emerald-50 flex items-center justify-center mb-2">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            </div>
            <p class="text-sm font-medium text-gray-500">All caught up — no entries pending approval</p>
        </div>
        <div id="appr-entries-error" class="hidden px-5 py-6 text-center text-sm text-red-500"></div>
    </div>

    <!-- Department Budgets Pending -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-3.5 border-b border-gray-100 flex items-center gap-2">
            <div class="w-2 h-2 rounded-full bg-purple-400"></div>
            <h3 class="font-semibold text-gray-800 text-sm">Department Budgets — Pending Approval</h3>
        </div>
        <div id="appr-budgets-loading" class="px-5 py-6 text-center text-sm text-gray-400">Loading…</div>
        <div id="appr-budgets-wrap" class="hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Department</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Month</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Amount</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Prepared By</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="appr-budgets-tbody" class="divide-y divide-gray-100"></tbody>
                </table>
            </div>
        </div>
        <div id="appr-budgets-empty" class="hidden px-5 py-10 text-center">
            <div class="w-10 h-10 mx-auto rounded-full bg-emerald-50 flex items-center justify-center mb-2">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            </div>
            <p class="text-sm font-medium text-gray-500">No department budgets pending approval</p>
        </div>
        <div id="appr-budgets-error" class="hidden px-5 py-6 text-center text-sm text-red-500"></div>
    </div>
</div>

<!-- ═══════════ TAB 7: RIPOTI ═══════════ -->
<div id="tab-ripoti" class="fin-panel hidden">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center cursor-pointer hover:border-primary-300 transition-colors" onclick="generateReport('monthly')">
            <div class="w-12 h-12 mx-auto rounded-xl bg-mist-100 flex items-center justify-center mb-3">
                <svg class="w-6 h-6 text-royal-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <h3 class="font-semibold text-gray-900">Monthly Report</h3>
            <p class="text-sm text-gray-500 mt-1">Income, Expenses, Balance</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center cursor-pointer hover:border-primary-300 transition-colors" onclick="generateReport('category')">
            <div class="w-12 h-12 mx-auto rounded-xl bg-mist-100 flex items-center justify-center mb-3">
                <svg class="w-6 h-6 text-royal-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
            </div>
            <h3 class="font-semibold text-gray-900">Category Report</h3>
            <p class="text-sm text-gray-500 mt-1">Income & expense breakdown</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center cursor-pointer hover:border-primary-300 transition-colors" onclick="generateReport('pledge')">
            <div class="w-12 h-12 mx-auto rounded-xl bg-mist-100 flex items-center justify-center mb-3">
                <svg class="w-6 h-6 text-royal-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="font-semibold text-gray-900">Pledge Report</h3>
            <p class="text-sm text-gray-500 mt-1">Pledge status & payments</p>
        </div>
    </div>
    <div id="report-output" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hidden">
        <div class="flex items-center justify-between mb-4">
            <h3 id="report-title" class="font-semibold text-gray-800">Report</h3>
            <button onclick="printReport()" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs rounded-lg flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z"/></svg>
                Print
            </button>
        </div>
        <div id="report-content"></div>
    </div>
</div>

<!-- ═══════════ MODALS ═══════════ -->

<!-- Add Finance Entry Modal -->
<div id="entry-modal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-gray-900/50" onclick="closeModal('entry-modal')"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 z-10">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-gray-900">New Finance Entry</h3>
                <button onclick="closeModal('entry-modal')" class="p-1 hover:bg-gray-100 rounded-lg">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form id="entry-form" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Entry No</label>
                        <input name="entry_no" id="entry-no-input" placeholder="Auto" class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                        <input type="date" name="entry_date" required class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select name="category_id" id="entry-category" required class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary-500">
                            <option value="">Loading...</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Amount (TZS)</label>
                        <input name="amount" type="number" step="0.01" min="0" placeholder="0.00" required class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                        <select name="payment_method" required class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary-500">
                            <option value="">Select...</option>
                            <option value="cash">Cash</option>
                            <option value="mobile_money">Mobile Money</option>
                            <option value="bank_transfer">Bank Transfer</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Member</label>
                        <select name="member_id" id="entry-member" class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary-500">
                            <option value="">Optional...</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <input name="description" placeholder="Brief description..." required class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary-500">
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="closeModal('entry-modal')" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 rounded-xl shadow-sm">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Pledge Modal -->
<div id="pledge-modal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-gray-900/50" onclick="closeModal('pledge-modal')"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 z-10">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-gray-900">New Pledge</h3>
                <button onclick="closeModal('pledge-modal')" class="p-1 hover:bg-gray-100 rounded-lg">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form id="pledge-form" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Member</label>
                    <select name="member_id" id="pledge-member" required class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary-500">
                        <option value="">Select member...</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pledge Amount (TZS)</label>
                        <input name="total_amount" type="number" step="0.01" min="0" required class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Campaign</label>
                        <input name="campaign" placeholder="e.g. Building Fund" class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pledge Date</label>
                        <input type="date" name="pledge_date" required class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Due Date</label>
                        <input type="date" name="due_date" class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <input name="description" placeholder="Description..." class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary-500">
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="closeModal('pledge-modal')" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 rounded-xl shadow-sm">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Budget Modal -->
<div id="budget-modal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-gray-900/50" onclick="closeModal('budget-modal')"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 z-10">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-gray-900">New Budget</h3>
                <button onclick="closeModal('budget-modal')" class="p-1 hover:bg-gray-100 rounded-lg">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form id="budget-form" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                        <input name="department" placeholder="e.g. Worship" required class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Month</label>
                        <input type="month" name="fiscal_month" required class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Budget Amount (TZS)</label>
                    <input name="planned_amount" type="number" step="0.01" min="0" required class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <input name="notes" placeholder="Budget notes..." class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary-500">
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="closeModal('budget-modal')" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 rounded-xl shadow-sm">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Event Budget Review Modal -->
<div id="event-budget-review-modal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-gray-900/50" onclick="closeModal('event-budget-review-modal')"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-5xl p-6 z-10">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Event Budget Review</h3>
                <button onclick="closeModal('event-budget-review-modal')" class="p-1 hover:bg-gray-100 rounded-lg">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div id="budget-review-summary" class="mb-4 text-sm text-gray-700"></div>
            <div class="overflow-x-auto border border-gray-100 rounded-xl">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Item</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Type</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Planned</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Actual</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Review Note</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody id="budget-review-tbody" class="divide-y divide-gray-100"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>

<script>
const API = BASE_URL + '/api/v1';
let categories = [];
let trendChart = null, categoryChart = null;
let activeBudgetReviewEventId = null;

function fmt(n) { return 'TZS ' + Number(n||0).toLocaleString('en-US', {minimumFractionDigits: 0}); }
function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

// ──── TOAST SYSTEM ────
function showToast(message, type = 'success', duration = 4000) {
    const container = document.getElementById('toast-container') || createToastContainer();
    const toast = document.createElement('div');
    const bgClass = type === 'error' ? 'bg-red-600' : type === 'info' ? 'bg-royal-600' : 'bg-emerald-600';
    
    toast.className = `${bgClass} text-white px-4 py-3 rounded-xl shadow-lg text-sm font-medium mb-2`;
    toast.textContent = message;
    container.appendChild(toast);
    
    setTimeout(() => toast.remove(), duration);
}

function createToastContainer() {
    const container = document.createElement('div');
    container.id = 'toast-container';
    container.className = 'fixed top-6 right-6 z-50 flex flex-col gap-2';
    document.body.appendChild(container);
    return container;
}

// ──── CONFIRMATION DIALOG ────
function showConfirmDialog(title, message, actionLabel, onConfirm, onCancel = null, isDestructive = false) {
    let dialog = document.getElementById('confirm-dialog-fin');
    if (!dialog) {
        dialog = document.createElement('div');
        dialog.id = 'confirm-dialog-fin';
        dialog.className = 'hidden fixed inset-0 z-50 overflow-y-auto';
        dialog.innerHTML = `
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-900/50" onclick="closeConfirmDialog()"></div>
                <div class="relative bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
                    <h3 id="confirm-title-fin" class="text-lg font-semibold text-royal-900 mb-2"></h3>
                    <p id="confirm-message-fin" class="text-mist-600 text-sm mb-6 whitespace-pre-line"></p>
                    <div class="flex gap-3 justify-end">
                        <button onclick="closeConfirmDialog()" class="px-4 py-2.5 rounded-xl bg-mist-100 text-mist-700 hover:bg-mist-200 text-sm font-semibold transition">Cancel</button>
                        <button id="confirm-btn-fin" onclick="executeConfirmDialog()" class="px-4 py-2.5 rounded-xl text-white text-sm font-semibold transition flex items-center gap-2">
                            <span id="confirm-btn-text-fin">Confirm</span>
                            <span id="confirm-spinner-fin" class="hidden w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                        </button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(dialog);
    }
    
    document.getElementById('confirm-title-fin').textContent = title;
    document.getElementById('confirm-message-fin').textContent = message;
    const btn = document.getElementById('confirm-btn-fin');
    btn.className = isDestructive 
        ? 'px-4 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-semibold transition flex items-center gap-2'
        : 'px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold transition flex items-center gap-2';
    document.getElementById('confirm-btn-text-fin').textContent = actionLabel;
    
    dialog.classList.remove('hidden');
    
    window.confirmDialogFnCallbacks = { onConfirm, onCancel };
}

function closeConfirmDialog() {
    const dialog = document.getElementById('confirm-dialog-fin');
    if (dialog) dialog.classList.add('hidden');
    const btn = document.getElementById('confirm-btn-fin');
    btn.disabled = false;
    document.getElementById('confirm-spinner-fin').classList.add('hidden');
    document.getElementById('confirm-btn-text-fin').classList.remove('hidden');
    if (window.confirmDialogFnCallbacks?.onCancel) window.confirmDialogFnCallbacks.onCancel();
}

async function executeConfirmDialog() {
    const btn = document.getElementById('confirm-btn-fin');
    btn.disabled = true;
    document.getElementById('confirm-spinner-fin').classList.remove('hidden');
    document.getElementById('confirm-btn-text-fin').classList.add('hidden');
    
    if (window.confirmDialogFnCallbacks?.onConfirm) {
        try {
            await window.confirmDialogFnCallbacks.onConfirm();
        } catch (e) {
            console.error('Dialog action failed:', e);
        }
    }
    
    closeConfirmDialog();
}

// ─── Tab switching ───
document.querySelectorAll('.fin-tab').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.fin-tab').forEach(t => { t.classList.remove('fin-tab-active','border-royal-600','text-royal-700'); t.classList.add('border-transparent','text-mist-500'); });
        btn.classList.add('fin-tab-active','border-royal-600','text-royal-700');
        btn.classList.remove('border-transparent','text-mist-500');
        document.querySelectorAll('.fin-panel').forEach(p => p.classList.add('hidden'));
        document.getElementById('tab-' + btn.dataset.tab).classList.remove('hidden');

        const tab = btn.dataset.tab;
        if (tab === 'muhtasari') loadOverview();
        else if (tab === 'mapato') loadIncome();
        else if (tab === 'matumizi') loadExpenses();
        else if (tab === 'ahadi') { loadPledges(); loadPledgeStats(); }
        else if (tab === 'bajeti') loadBudgets();
        else if (tab === 'idhinisho') loadApprovals();
    });
});

// ─── Month selector ───
function buildMonthSelector() {
    const sel = document.getElementById('fin-month-select');
    const now = new Date();
    for (let i = 0; i < 12; i++) {
        const d = new Date(now.getFullYear(), now.getMonth() - i, 1);
        // Use local date parts — toISOString() converts to UTC which shifts the month in UTC+3 timezones
        const val = d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0');
        const label = d.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
        sel.innerHTML += '<option value="' + val + '"' + (i === 0 ? ' selected' : '') + '>' + label + '</option>';
    }
    sel.addEventListener('change', () => loadOverview());

    const bSel = document.getElementById('bajeti-month-filter');
    bSel.innerHTML = '<option value="">All Months</option>' + sel.innerHTML;
    bSel.addEventListener('change', () => loadBudgets());
}

// ─── Load Categories & Members ───
async function loadMeta() {
    try {
        const [catRes, memRes] = await Promise.all([
            fetch(API + '/finance/categories'),
            fetch(API + '/members?status=active')
        ]);
        const catData = await catRes.json();
        const memData = await memRes.json();
        categories = catData.data || [];

        const catSel = document.getElementById('entry-category');
        catSel.innerHTML = '<option value="">Select category...</option>';
        categories.forEach(c => {
            catSel.innerHTML += '<option value="' + c.id + '">' + c.name + ' (' + (c.category_type === 'income' ? 'Income' : 'Expense') + ')</option>';
        });

        const mapatoCat = document.getElementById('mapato-cat-filter');
        const matumiziCat = document.getElementById('matumizi-cat-filter');
        categories.forEach(c => {
            if (c.category_type === 'income') mapatoCat.innerHTML += '<option value="' + c.id + '">' + c.name + '</option>';
            else matumiziCat.innerHTML += '<option value="' + c.id + '">' + c.name + '</option>';
        });

        const members = memData.data || [];
        ['entry-member', 'pledge-member'].forEach(selId => {
            const s = document.getElementById(selId);
            s.innerHTML = '<option value="">Select member...</option>';
            members.forEach(m => {
                s.innerHTML += '<option value="' + m.id + '">' + m.first_name + ' ' + m.last_name + ' (' + m.member_code + ')</option>';
            });
        });
    } catch (e) { console.error('Meta load failed:', e); }
}

// ═══════════ TAB 1: MUHTASARI ═══════════
async function loadOverview() {
    const month = document.getElementById('fin-month-select').value;
    try {
        const res = await fetch(API + '/finance/overview?month=' + month);
        const d = (await res.json()).data;
        if (!d) return;

        // ── Month label ──
        const [yr, mo] = month.split('-');
        const monthName = new Date(yr, mo - 1, 1).toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
        document.querySelectorAll('#kpi-month-label').forEach(el => el.textContent = monthName);

        // ── KPI values ──
        document.getElementById('kpi-income').textContent   = fmt(d.month_income);
        document.getElementById('kpi-expense').textContent  = fmt(d.month_expense);
        document.getElementById('kpi-balance').textContent  = fmt(d.month_balance);
        document.getElementById('kpi-alltime').textContent  = 'All-time: ' + fmt(d.all_time_balance);

        // Balance label colour hint
        const balLabel = document.getElementById('kpi-balance-label');
        balLabel.textContent = d.month_balance >= 0 ? '▲ Surplus' : '▼ Deficit';

        // Pending counts (two sets: KPI card + Pledge panel)
        const ea = d.pending_approvals || 0;
        const ba = d.pending_budgets   || 0;
        ['pending-entries-count','pending-entries-count2'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.textContent = ea;
        });
        ['pending-budgets-count','pending-budgets-count2'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.textContent = ba;
        });
        const totalPending = ea + ba;
        document.getElementById('kpi-pending-total').textContent = totalPending;
        document.getElementById('kpi-pledges').textContent = fmt(d.pending_pledges);

        // Approval tab badge
        const badge = document.getElementById('approval-badge');
        if (totalPending > 0) { badge.textContent = totalPending; badge.classList.remove('hidden'); }
        else badge.classList.add('hidden');

        // ── Progress bars on KPI cards ──
        const total = d.month_income + d.month_expense;
        if (total > 0) {
            document.getElementById('kpi-income-bar').style.width  = Math.min(100, (d.month_income  / total * 100)).toFixed(1) + '%';
            document.getElementById('kpi-expense-bar').style.width = Math.min(100, (d.month_expense / total * 100)).toFixed(1) + '%';
        }

        // ── Health bar ──
        const healthBar   = document.getElementById('health-bar');
        const healthLabel = document.getElementById('health-label');
        if (total > 0) {
            const incomePct = d.month_income / total * 100;
            healthBar.style.width = incomePct.toFixed(1) + '%';
            if (incomePct >= 60) {
                healthBar.className = 'h-3 rounded-full transition-all duration-1000 bg-emerald-500';
                healthLabel.textContent = 'Healthy — income exceeds expenses';
            } else if (incomePct >= 45) {
                healthBar.className = 'h-3 rounded-full transition-all duration-1000 bg-amber-400';
                healthLabel.textContent = 'Caution — nearly balanced';
            } else {
                healthBar.className = 'h-3 rounded-full transition-all duration-1000 bg-red-500';
                healthLabel.textContent = 'Alert — expenses exceed income';
            }
        } else {
            healthBar.style.width = '0%';
            healthLabel.textContent = 'No data for this month';
        }

        // ── Recent entries ──
        const tbody  = document.getElementById('recent-tbody');
        const empty  = document.getElementById('recent-empty');
        const entries = d.recent_entries || [];
        if (entries.length === 0) {
            tbody.innerHTML = ''; empty.classList.remove('hidden');
        } else {
            empty.classList.add('hidden');
            tbody.innerHTML = entries.slice(0, 10).map(r => {
                const isIncome = r.category_type === 'income';
                return '<tr class="hover:bg-gray-50 transition">' +
                    '<td class="py-2.5 text-xs text-gray-500 whitespace-nowrap">' + (r.entry_date || '-') + '</td>' +
                    '<td class="py-2.5 text-xs font-semibold text-gray-800 max-w-[140px] truncate">' + (r.category_name || '-') + '</td>' +
                    '<td class="py-2.5"><span class="px-2 py-0.5 rounded-md text-[10px] font-bold ' + (isIncome ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700') + '">' + (isIncome ? 'Income' : 'Expense') + '</span></td>' +
                    '<td class="py-2.5 text-right text-xs font-bold ' + (isIncome ? 'text-emerald-600' : 'text-red-600') + '">' + fmt(r.amount) + '</td></tr>';
            }).join('');
        }

        renderTrendChart(d.trend);
        renderCategoryChart(d.category_breakdown);
    } catch (e) { console.error('Overview failed:', e); }
}

function switchToApprovals() {
    document.querySelector('[data-tab="idhinisho"]')?.click();
}

function renderTrendChart(trend) {
    const ctx = document.getElementById('trend-chart');
    if (trendChart) trendChart.destroy();
    const months = Object.keys(trend).sort();
    if (months.length === 0) return;
    trendChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: months.map(m => new Date(m + '-01').toLocaleDateString('en-US', { month: 'short', year: '2-digit' })),
            datasets: [
                {
                    label: 'Income',
                    data: months.map(m => trend[m]?.income || 0),
                    backgroundColor: 'rgba(34,197,94,0.85)',
                    borderRadius: 8,
                    borderSkipped: false,
                },
                {
                    label: 'Expenses',
                    data: months.map(m => trend[m]?.expense || 0),
                    backgroundColor: 'rgba(239,68,68,0.85)',
                    borderRadius: 8,
                    borderSkipped: false,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 11 } } },
                y: {
                    beginAtZero: true,
                    grid: { color: '#f3f4f6' },
                    ticks: {
                        font: { size: 10 },
                        callback: v => v >= 1000000 ? 'TZS ' + (v/1000000).toFixed(1) + 'M'
                                     : v >= 1000    ? 'TZS ' + (v/1000).toFixed(0) + 'K'
                                     : 'TZS ' + v
                    }
                }
            }
        }
    });
}

function renderCategoryChart(breakdown) {
    const ctx = document.getElementById('category-chart');
    if (categoryChart) categoryChart.destroy();
    const legend = document.getElementById('category-legend');
    if (!breakdown || breakdown.length === 0) { legend.innerHTML = '<p class="text-xs text-gray-400 text-center">No data</p>'; return; }

    const palette = ['#22c55e','#3b82f6','#f59e0b','#ef4444','#8b5cf6','#ec4899','#06b6d4','#84cc16','#f97316','#6366f1'];
    const colors  = breakdown.map((_, i) => palette[i % palette.length]);
    const total   = breakdown.reduce((s, c) => s + parseFloat(c.total), 0);

    categoryChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: breakdown.map(c => c.name),
            datasets: [{
                data: breakdown.map(c => parseFloat(c.total)),
                backgroundColor: colors,
                borderWidth: 2,
                borderColor: '#fff',
                hoverOffset: 6,
            }]
        },
        options: {
            responsive: true,
            cutout: '65%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ' ' + ctx.label + ': ' + fmt(ctx.raw) + ' (' + (ctx.raw / total * 100).toFixed(1) + '%)'
                    }
                }
            }
        }
    });

    // Custom legend
    legend.innerHTML = breakdown.slice(0, 6).map((c, i) =>
        '<div class="flex items-center justify-between text-xs">' +
        '<span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full flex-shrink-0" style="background:' + colors[i] + '"></span>' +
        '<span class="text-gray-600 truncate max-w-[110px]">' + c.name + '</span></span>' +
        '<span class="font-semibold text-gray-700 ml-2">' + (total > 0 ? (parseFloat(c.total)/total*100).toFixed(1) + '%' : '—') + '</span>' +
        '</div>'
    ).join('') + (breakdown.length > 6 ? '<p class="text-[10px] text-gray-400 text-center mt-1">+' + (breakdown.length - 6) + ' more</p>' : '');
}

// ═══════════ TAB 2: MAPATO ═══════════
// shared row renderer used by both income and expense tabs
function renderEntryRow(r, amountClass, showSource) {
    const isPending  = r.approval_status === 'pending';
    const rowOpacity = isPending ? ' opacity-60' : '';
    const statusBadge = isPending
        ? '<span class="ml-1.5 px-1.5 py-0.5 text-[10px] bg-amber-100 text-amber-700 rounded-full font-semibold align-middle">⏳ Pending</span>'
        : '';
    const sourceCell = showSource
        ? '<td class="px-4 py-3 text-xs"><span class="px-2 py-0.5 rounded-md capitalize ' + (r.source_type === 'event' ? 'bg-blue-50 text-blue-700' : 'bg-gray-100 text-gray-600') + '">' + (r.source_type || '-') + '</span></td>'
        : '';
    const printBtn = (!isPending)
        ? '<button onclick="printEntryReceipt(' + r.id + ')" class="p-1.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg transition text-xs" title="Print Receipt">🖨️</button>'
        : '';
    return '<tr class="hover:bg-gray-50 transition' + rowOpacity + '">' +
        '<td class="px-4 py-3 text-xs text-gray-500 whitespace-nowrap">' + (r.entry_date || '-') + '</td>' +
        '<td class="px-4 py-3 text-xs font-mono text-gray-400 whitespace-nowrap">' + (r.entry_no || '') + statusBadge + '</td>' +
        '<td class="px-4 py-3 text-sm font-semibold text-gray-800">' + (r.category_name || '-') + '</td>' +
        (showSource ? sourceCell : '<td class="px-4 py-3 text-xs text-gray-500">' + (r.first_name ? r.first_name + ' ' + r.last_name : '-') + '</td>') +
        '<td class="px-4 py-3 text-right font-bold ' + amountClass + ' whitespace-nowrap">' + (isPending ? '<span class="line-through opacity-50">' + fmt(r.amount) + '</span>' : fmt(r.amount)) + '</td>' +
        '<td class="px-4 py-3 text-xs text-gray-500 capitalize">' + (r.payment_method || '').replace('_', ' ') + '</td>' +
        '<td class="px-4 py-3 text-xs text-gray-400 truncate max-w-[160px]" title="' + (r.description || '').replace(/"/g, '&quot;') + '">' + (r.description || '-') + '</td>' +
        '<td class="px-4 py-3 text-center">' + printBtn + '</td>' +
        '</tr>';
}

async function loadIncome() {
    const cat = document.getElementById('mapato-cat-filter').value;
    const from = document.getElementById('mapato-from').value;
    const to = document.getElementById('mapato-to').value;
    let url = API + '/finance/entries/filtered?type=income';
    if (cat) url += '&category=' + cat;
    if (from) url += '&date_from=' + from;
    if (to) url += '&date_to=' + to;
    try {
        const res = await fetch(url);
        const rows = (await res.json()).data || [];
        const tbody = document.getElementById('mapato-tbody');
        const empty = document.getElementById('mapato-empty');
        if (rows.length === 0) { tbody.innerHTML = ''; empty.classList.remove('hidden'); }
        else {
            empty.classList.add('hidden');
            tbody.innerHTML = rows.map(r => renderEntryRow(r, 'text-emerald-600', false)).join('');
        }
        // Totals only sum approved entries
        const approvedRows = rows.filter(r => !r.approval_status || r.approval_status === 'approved');
        document.getElementById('mapato-count').textContent = approvedRows.length + ' approved' + (rows.length > approvedRows.length ? ' (' + (rows.length - approvedRows.length) + ' pending)' : '');
        document.getElementById('mapato-total').textContent = fmt(approvedRows.reduce((s, r) => s + parseFloat(r.amount), 0));
    } catch (e) { console.error('Income failed:', e); }
}

// ═══════════ TAB 3: MATUMIZI ═══════════
async function loadExpenses() {
    const cat = document.getElementById('matumizi-cat-filter').value;
    const from = document.getElementById('matumizi-from')?.value || '';
    const to   = document.getElementById('matumizi-to')?.value   || '';
    let url = API + '/finance/entries/filtered?type=expense';
    if (cat) url += '&category=' + cat;
    if (from) url += '&date_from=' + from;
    if (to)   url += '&date_to=' + to;
    try {
        const res = await fetch(url);
        const rows = (await res.json()).data || [];
        const tbody = document.getElementById('matumizi-tbody');
        const empty = document.getElementById('matumizi-empty');
        if (rows.length === 0) { tbody.innerHTML = ''; empty.classList.remove('hidden'); }
        else {
            empty.classList.add('hidden');
            tbody.innerHTML = rows.map(r => renderEntryRow(r, 'text-red-600', true)).join('');
        }
        // Totals only sum approved entries
        const approvedRows = rows.filter(r => !r.approval_status || r.approval_status === 'approved');
        document.getElementById('matumizi-count').textContent = approvedRows.length + ' approved' + (rows.length > approvedRows.length ? ' (' + (rows.length - approvedRows.length) + ' pending)' : '');
        document.getElementById('matumizi-total').textContent = fmt(approvedRows.reduce((s, r) => s + parseFloat(r.amount), 0));
    } catch (e) { console.error('Expenses failed:', e); }
}

// ═══════════ TAB 4: AHADI ═══════════
async function loadPledges() {
    try {
        const res = await fetch(API + '/finance/pledges');
        const rows = (await res.json()).data || [];
        const tbody = document.getElementById('ahadi-tbody');
        const empty = document.getElementById('ahadi-empty');
        const sc = { active:'bg-blue-100 text-blue-800', completed:'bg-green-100 text-green-800', overdue:'bg-red-100 text-red-800', cancelled:'bg-gray-100 text-gray-800' };
        if (rows.length === 0) { tbody.innerHTML = ''; empty.classList.remove('hidden'); }
        else {
            empty.classList.add('hidden');
            tbody.innerHTML = rows.map(r => {
                const pct = r.total_amount > 0 ? Math.round(r.paid_amount / r.total_amount * 100) : 0;
                return '<tr class="hover:bg-gray-50">' +
                    '<td class="px-4 py-3 text-xs font-mono text-gray-500">' + r.pledge_no + '</td>' +
                    '<td class="px-4 py-3 text-sm font-medium text-gray-900">' + r.first_name + ' ' + r.last_name + '</td>' +
                    '<td class="px-4 py-3 text-xs text-gray-600">' + (r.campaign || '-') + '</td>' +
                    '<td class="px-4 py-3 text-right font-semibold text-gray-900">' + fmt(r.total_amount) + '</td>' +
                    '<td class="px-4 py-3 text-right text-green-600 font-medium">' + fmt(r.paid_amount) + '</td>' +
                    '<td class="px-4 py-3 text-right text-amber-600 font-medium">' + fmt(r.balance) + '</td>' +
                    '<td class="px-4 py-3 text-xs text-gray-600">' + (r.due_date || '-') + '</td>' +
                    '<td class="px-4 py-3"><div class="flex items-center gap-2">' +
                    '<span class="px-2 py-0.5 rounded-full text-xs font-medium ' + (sc[r.status]||'bg-gray-100') + '">' + r.status + '</span>' +
                    '<div class="w-16 bg-gray-200 rounded-full h-1.5"><div class="bg-green-500 h-1.5 rounded-full" style="width:' + pct + '%"></div></div>' +
                    '<span class="text-xs text-gray-400">' + pct + '%</span></div></td></tr>';
            }).join('');
        }
    } catch (e) { console.error('Pledges failed:', e); }
}

async function loadPledgeStats() {
    try {
        const res = await fetch(API + '/finance/pledges/stats');
        const d = (await res.json()).data;
        document.getElementById('pledge-total-count').textContent = d.total || 0;
        document.getElementById('pledge-total-pledged').textContent = fmt(d.total_pledged);
        document.getElementById('pledge-total-paid').textContent = fmt(d.total_paid);
        document.getElementById('pledge-total-balance').textContent = fmt(d.total_balance);
    } catch (e) { console.error('Pledge stats failed:', e); }
}

// ═══════════ TAB 5: BAJETI ═══════════
async function loadBudgets() {
    const month = document.getElementById('bajeti-month-filter').value;
    let url = API + '/finance/budgets';
    if (month) url += '?month=' + month;
    try {
        const res = await fetch(url);
        const rows = (await res.json()).data || [];
        const tbody = document.getElementById('bajeti-tbody');
        const empty = document.getElementById('bajeti-empty');
        const sc = { draft:'bg-gray-100 text-gray-800', submitted:'bg-amber-100 text-amber-800', approved:'bg-green-100 text-green-800', rejected:'bg-red-100 text-red-800' };
        if (rows.length === 0) { tbody.innerHTML = ''; empty.classList.remove('hidden'); }
        else {
            empty.classList.add('hidden');
            tbody.innerHTML = rows.map(r => {
                const pct = parseFloat(r.percent_used) || 0;
                const barColor = pct >= 90 ? 'bg-red-500' : pct >= 70 ? 'bg-amber-500' : 'bg-green-500';
                return '<tr class="hover:bg-gray-50">' +
                    '<td class="px-4 py-3 text-sm font-medium text-gray-900">' + r.department + '</td>' +
                    '<td class="px-4 py-3 text-xs text-gray-600">' + r.fiscal_month + '</td>' +
                    '<td class="px-4 py-3 text-right font-semibold text-gray-900">' + fmt(r.planned_amount) + '</td>' +
                    '<td class="px-4 py-3 text-right text-red-600 font-medium">' + fmt(r.spent_amount) + '</td>' +
                    '<td class="px-4 py-3"><div class="flex items-center gap-2">' +
                    '<div class="w-20 bg-gray-200 rounded-full h-2"><div class="' + barColor + ' h-2 rounded-full" style="width:' + Math.min(pct,100) + '%"></div></div>' +
                    '<span class="text-xs ' + (pct>=90?'text-red-600 font-bold':'text-gray-500') + '">' + pct + '%</span>' +
                    (pct >= 90 ? ' <span class="text-xs">⚠️</span>' : '') + '</div></td>' +
                    '<td class="px-4 py-3"><span class="px-2 py-0.5 rounded-full text-xs font-medium ' + (sc[r.status]||'') + '">' + r.status + '</span></td>' +
                    '<td class="px-4 py-3 text-xs text-gray-600">' + (r.approved_by_name || '-') + '</td></tr>';
            }).join('');
        }
    } catch (e) { console.error('Budgets failed:', e); }
}

// ═══════════ TAB 6: IDHINISHO ═══════════
async function loadApprovals() {
    // --- helpers ---
    function setApprState(prefix, state, msg) {
        ['loading','wrap','empty','error'].forEach(s => {
            const el = document.getElementById('appr-' + prefix + '-' + s);
            if (el) el.classList.toggle('hidden', s !== state);
        });
        if (state === 'error' && msg) {
            const el = document.getElementById('appr-' + prefix + '-error');
            if (el) el.textContent = msg;
        }
    }

    setApprState('entries', 'loading');
    setApprState('budgets', 'loading');

    // ── Finance Entries (independent) ─────────────────────────────────────
    try {
        const res = await fetch(API + '/finance/entries/filtered?approval=pending');
        if (!res.ok) throw new Error('Server error ' + res.status);
        const payload = await res.json();
        if (!payload.success) throw new Error(payload.message || 'Failed to load entries');
        const entries = payload.data || [];

        const badge = document.getElementById('appr-entry-count-badge');
        if (entries.length > 0) {
            badge.textContent = entries.length + ' entr' + (entries.length === 1 ? 'y' : 'ies') + ' pending';
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }

        const tbody = document.getElementById('appr-entries-tbody');
        if (entries.length === 0) {
            tbody.innerHTML = '';
            setApprState('entries', 'empty');
        } else {
            tbody.innerHTML = entries.map(r => {
                const rejCount = parseInt(r.rejection_count || 0);
                const resubBadge = rejCount > 0
                    ? '<span class="ml-1 px-1.5 py-0.5 text-[10px] bg-amber-100 text-amber-700 rounded-full font-semibold">Resubmitted' + (rejCount > 1 ? ' \xd7' + rejCount : '') + '</span>'
                    : '';
                const srcColor = r.source_type === 'event' ? 'bg-blue-50 text-blue-700' : 'bg-gray-100 text-gray-600';
                return '<tr class="hover:bg-gray-50 transition">' +
                    '<td class="px-4 py-3 text-xs font-mono text-mist-600 whitespace-nowrap">' + (r.entry_no || '-') + resubBadge + '</td>' +
                    '<td class="px-4 py-3 text-xs text-mist-600 whitespace-nowrap">' + (r.entry_date || '-') + '</td>' +
                    '<td class="px-4 py-3 text-sm text-royal-800 font-medium">' + (r.category_name || '-') + '</td>' +
                    '<td class="px-4 py-3 text-right font-semibold text-red-600 whitespace-nowrap">' + fmt(r.amount) + '</td>' +
                    '<td class="px-4 py-3 text-xs"><span class="px-2 py-0.5 rounded-md ' + srcColor + ' font-medium capitalize">' + (r.source_type || '-') + '</span></td>' +
                    '<td class="px-4 py-3 text-xs text-mist-500 max-w-[160px] truncate" title="' + (r.description || '').replace(/"/g, '&quot;') + '">' + (r.description || '-') + '</td>' +
                    '<td class="px-4 py-3 text-xs text-mist-600">' + (r.recorded_by_name || '-') + '</td>' +
                    '<td class="px-4 py-3 text-center">' +
                    '<div class="flex items-center justify-center gap-1.5 flex-wrap">' +
                    '<button onclick="approveEntry(' + r.id + ',\'approved\')" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs rounded-lg font-semibold transition active:scale-95">Approve</button>' +
                    '<button onclick="approveEntry(' + r.id + ',\'rejected\')" class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs rounded-lg font-semibold transition active:scale-95">Reject</button>' +
                    (r.source_type === 'event' && r.event_id ? '<button onclick="openEventBudgetReview(' + r.event_id + ')" class="px-3 py-1.5 bg-royal-700 hover:bg-royal-800 text-white text-xs rounded-lg font-semibold transition active:scale-95">View Budget</button>' : '') +
                    '</div></td></tr>';
            }).join('');
            setApprState('entries', 'wrap');
        }
    } catch (e) {
        console.error('Entries load failed:', e);
        setApprState('entries', 'error', 'Could not load entries: ' + e.message);
    }

    // ── Department Budgets (independent) ──────────────────────────────────
    try {
        const res = await fetch(API + '/finance/budgets?status=submitted');
        if (!res.ok) throw new Error('Server error ' + res.status);
        const payload = await res.json();
        if (!payload.success) throw new Error(payload.message || 'Failed to load budgets');
        const budgets = payload.data || [];

        const badge = document.getElementById('appr-budget-count-badge');
        if (budgets.length > 0) {
            badge.textContent = budgets.length + ' budget' + (budgets.length === 1 ? '' : 's') + ' pending';
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }

        const tbody = document.getElementById('appr-budgets-tbody');
        if (budgets.length === 0) {
            tbody.innerHTML = '';
            setApprState('budgets', 'empty');
        } else {
            tbody.innerHTML = budgets.map(r =>
                '<tr class="hover:bg-gray-50 transition">' +
                '<td class="px-4 py-3 text-sm font-semibold text-royal-800">' + (r.department || '-') + '</td>' +
                '<td class="px-4 py-3 text-xs text-mist-600">' + (r.fiscal_month || '-') + '</td>' +
                '<td class="px-4 py-3 text-right font-semibold text-mist-700 whitespace-nowrap">' + fmt(r.planned_amount) + '</td>' +
                '<td class="px-4 py-3 text-xs text-mist-600">' + (r.submitted_by_name || '-') + '</td>' +
                '<td class="px-4 py-3 text-center">' +
                '<div class="flex items-center justify-center gap-1.5">' +
                '<button onclick="approveBudget(' + r.id + ',\'approved\')" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs rounded-lg font-semibold transition active:scale-95">Approve</button>' +
                '<button onclick="approveBudget(' + r.id + ',\'rejected\')" class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs rounded-lg font-semibold transition active:scale-95">Reject</button>' +
                '</div></td></tr>'
            ).join('');
            setApprState('budgets', 'wrap');
        }
    } catch (e) {
        console.error('Budgets load failed:', e);
        setApprState('budgets', 'error', 'Could not load department budgets: ' + e.message);
    }
}

async function openEventBudgetReview(eventId) {
    activeBudgetReviewEventId = eventId;
    try {
        const res = await fetch(API + '/events/' + eventId + '/details');
        const payload = await res.json();
        if (!res.ok || !payload.success) {
            throw new Error(payload.message || 'Unable to load event budget details');
        }

        const data = payload.data;
        const summary = document.getElementById('budget-review-summary');
        summary.innerHTML = '<strong>Event:</strong> ' + (data.overview?.title || '-') +
            ' | <strong>Total Budget:</strong> ' + fmt(data.overview?.budget_total || 0) +
            ' | <strong>Actual:</strong> ' + fmt(data.budget?.actual_expenses || 0);

        const tbody = document.getElementById('budget-review-tbody');
        const rows = data.budget?.items || [];
        tbody.innerHTML = rows.map(item =>
            '<tr>' +
            '<td class="px-3 py-2"><input id="rv-name-' + item.id + '" class="w-full border border-gray-300 rounded px-2 py-1 text-xs" value="' + (item.item_name || '') + '"></td>' +
            '<td class="px-3 py-2"><select id="rv-type-' + item.id + '" class="border border-gray-300 rounded px-2 py-1 text-xs"><option value="income"' + (item.item_type === 'income' ? ' selected' : '') + '>income</option><option value="expense"' + (item.item_type === 'expense' ? ' selected' : '') + '>expense</option></select></td>' +
            '<td class="px-3 py-2"><input id="rv-planned-' + item.id + '" type="number" min="0" step="0.01" class="w-28 border border-gray-300 rounded px-2 py-1 text-xs" value="' + (item.planned_amount || 0) + '"></td>' +
            '<td class="px-3 py-2"><input id="rv-actual-' + item.id + '" type="number" min="0" step="0.01" class="w-28 border border-gray-300 rounded px-2 py-1 text-xs" value="' + (item.actual_amount || 0) + '"></td>' +
            '<td class="px-3 py-2"><input id="rv-note-' + item.id + '" class="w-full border border-gray-300 rounded px-2 py-1 text-xs" placeholder="Reason / correction" value="' + (item.notes || '') + '"></td>' +
            '<td class="px-3 py-2"><div class="flex gap-1"><button onclick="saveBudgetReviewItem(' + item.id + ', false)" class="px-2 py-1 bg-blue-600 text-white rounded text-xs font-semibold">Save</button><button onclick="saveBudgetReviewItem(' + item.id + ', true)" class="px-2 py-1 bg-red-600 text-white rounded text-xs font-semibold">Mark Rejected</button></div></td>' +
            '</tr>'
        ).join('');

        openModal('event-budget-review-modal');
    } catch (e) {
        showToast(`✗ ${e.message}`, 'error');
    }
}

async function saveBudgetReviewItem(itemId, markRejected) {
    if (!activeBudgetReviewEventId) return;
    try {
        const note = document.getElementById('rv-note-' + itemId).value || '';
        const payload = {
            item_name: document.getElementById('rv-name-' + itemId).value || '',
            item_type: document.getElementById('rv-type-' + itemId).value || 'expense',
            planned_amount: document.getElementById('rv-planned-' + itemId).value || 0,
            actual_amount: document.getElementById('rv-actual-' + itemId).value || 0,
            notes: markRejected ? ('[REJECTED] ' + note) : note,
        };

        const res = await fetch(API + '/events/' + activeBudgetReviewEventId + '/budget-items/' + itemId, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload),
        });
        const data = await res.json();
        
        if (!res.ok || !data.success) {
            throw new Error(data.message || 'Failed to save');
        }
        
        showToast(markRejected ? '✓ Item marked as rejected and saved' : '✓ Budget item updated', 'success');
        await openEventBudgetReview(activeBudgetReviewEventId);
    } catch (e) {
        showToast(`✗ ${e.message}`, 'error');
    }
}

async function approveEntry(id, decision) {
    try {
        const title = decision === 'approved' ? '✓ Approve Entry?' : '✗ Reject Entry?';
        const message = decision === 'approved'
            ? `Approve entry #${id}? A receipt will be available to print.`
            : `Reject entry #${id}? It will be sent back for revision.`;
        const isDestructive = decision === 'rejected';
        
        showConfirmDialog(
            title,
            message,
            decision === 'approved' ? 'Approve' : 'Reject',
            async () => {
                try {
                    const res = await fetch(API + '/finance/entries/' + id + '/approve', {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ decision })
                    });
                    
                    if (!res.ok) {
                        const err = await res.json();
                        throw new Error(err.message || `Failed to ${decision}`);
                    }
                    
                    const data = await res.json();
                    showToast(`✓ Entry ${decision} successfully`, 'success');
                    await Promise.all([ loadApprovals(), loadOverview() ]);

                    // Offer print receipt after approval
                    if (decision === 'approved') {
                        setTimeout(() => {
                            showConfirmDialog(
                                '🖨️ Print Receipt?',
                                'Entry approved. Would you like to print a receipt?',
                                'Print Receipt',
                                () => printEntryReceipt(id),
                                null,
                                false
                            );
                        }, 400);
                    }
                } catch (e) {
                    console.error('Approve failed:', e);
                    showToast(`✗ ${e.message}`, 'error');
                }
            },
            null,
            isDestructive
        );
    } catch (e) {
        console.error('approveEntry error:', e);
        showToast(`✗ Error: ${e.message}`, 'error');
    }
}

// ── Print receipt for an approved finance entry ──
async function printEntryReceipt(entryId) {
    try {
        const res = await fetch(API + '/finance/entries/filtered?approval=approved');
        const entries = (await res.json()).data || [];
        const entry = entries.find(e => e.id == entryId);
        
        if (!entry) {
            // Try fetching all entries as fallback
            const res2 = await fetch(API + '/finance/entries/filtered');
            const all = (await res2.json()).data || [];
            const fallback = all.find(e => e.id == entryId);
            if (!fallback) { showToast('Entry not found for receipt', 'error'); return; }
            Object.assign(entry || {}, fallback);
        }

        const e = entry || {};
        const receiptHtml = `<!DOCTYPE html><html><head><title>Finance Receipt - ${e.entry_no}</title>
        <style>
            body{font-family:Arial,sans-serif;max-width:600px;margin:auto;padding:30px;color:#333}
            .header{text-align:center;border-bottom:2px solid #1e3a5f;padding-bottom:16px;margin-bottom:20px}
            .header h1{font-size:20px;color:#1e3a5f;margin:0 0 4px}
            .header p{font-size:12px;color:#666;margin:0}
            .row{display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #eee;font-size:14px}
            .row .label{color:#666;font-weight:600}
            .row .value{color:#222;font-weight:500}
            .amount{font-size:22px;font-weight:bold;color:#1e3a5f;text-align:center;padding:16px 0;margin:16px 0;background:#f0f5ff;border-radius:8px}
            .stamp{margin-top:24px;padding:12px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;text-align:center;font-size:13px}
            .stamp strong{color:#166534}
            .footer{margin-top:30px;text-align:center;font-size:11px;color:#999}
            @media print{body{margin:0;padding:10px}.no-print{display:none}}
        </style></head><body>
        <div class="header">
            <h1>FINANCE RECEIPT</h1>
            <p>Official Transaction Record</p>
        </div>
        <div class="row"><span class="label">Entry No:</span><span class="value">${e.entry_no || 'N/A'}</span></div>
        <div class="row"><span class="label">Date:</span><span class="value">${e.entry_date || 'N/A'}</span></div>
        <div class="row"><span class="label">Category:</span><span class="value">${e.category_name || 'N/A'}</span></div>
        <div class="row"><span class="label">Payment Method:</span><span class="value" style="text-transform:capitalize">${e.payment_method || 'N/A'}</span></div>
        <div class="row"><span class="label">Source:</span><span class="value" style="text-transform:capitalize">${e.source_type || 'Manual'}</span></div>
        <div class="amount">TZS ${parseFloat(e.amount || 0).toLocaleString('en-US', {minimumFractionDigits:2})}</div>
        <div class="row"><span class="label">Description:</span><span class="value" style="max-width:300px;text-align:right">${e.description || '-'}</span></div>
        <div class="row"><span class="label">Recorded By:</span><span class="value">${e.recorded_by_name || '-'}</span></div>
        <div class="stamp">
            <strong>✓ APPROVED</strong><br>
            ${e.approved_by_name ? 'By: ' + e.approved_by_name : ''}
            ${e.approved_at ? ' &bull; ' + new Date(e.approved_at).toLocaleDateString('en-US', {year:'numeric',month:'long',day:'numeric'}) : ''}
        </div>
        <div class="footer">Generated on ${new Date().toLocaleDateString('en-US', {year:'numeric',month:'long',day:'numeric',hour:'2-digit',minute:'2-digit'})}</div>
        <div class="no-print" style="margin-top:20px;text-align:center">
            <button onclick="window.print()" style="padding:8px 24px;border:none;background:#1e3a5f;color:#fff;border-radius:6px;cursor:pointer;font-size:14px">Print</button>
            <button onclick="window.close()" style="padding:8px 24px;border:1px solid #ccc;background:#fff;color:#333;border-radius:6px;cursor:pointer;font-size:14px;margin-left:8px">Close</button>
        </div>
        </body></html>`;

        const w = window.open('', '_blank');
        w.document.write(receiptHtml);
        w.document.close();
    } catch (err) {
        console.error('Print receipt error:', err);
        showToast('Failed to generate receipt', 'error');
    }
}

async function approveBudget(id, decision) {
    try {
        console.log('approveBudget called with:', id, decision);
        const title = decision === 'approved' ? '✓ Approve Budget?' : '✗ Reject Budget?';
        const message = `Budget ID: ${id}\n\nThis action cannot be undone.`;
        const isDestructive = decision === 'rejected';
        
        console.log('Showing dialog:', title);
        showConfirmDialog(
            title,
            message,
            decision === 'approved' ? 'Approve' : 'Reject',
            async () => {
                console.log('Dialog confirmed, sending request to:', API + '/finance/budgets/' + id + '/approve');
                try {
                    const res = await fetch(API + '/finance/budgets/' + id + '/approve', {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ decision })
                    });
                    
                    console.log('Response status:', res.status, res.ok);
                    
                    if (!res.ok) {
                        const err = await res.json();
                        throw new Error(err.message || `Failed to ${decision}`);
                    }
                    
                    const data = await res.json();
                    console.log('Success response:', data);
                    showToast(`✓ Budget ${decision} successfully`, 'success');
                    await Promise.all([ loadApprovals(), loadBudgets(), loadOverview() ]);
                } catch (e) {
                    console.error('Approve budget failed:', e);
                    showToast(`✗ ${e.message}`, 'error');
                }
            },
            null,
            isDestructive
        );
    } catch (e) {
        console.error('approveBudget error:', e);
        showToast(`✗ Error: ${e.message}`, 'error');
    }
}

// ═══════════ TAB 7: RIPOTI ═══════════
async function generateReport(type) {
    const output = document.getElementById('report-output');
    const content = document.getElementById('report-content');
    const title = document.getElementById('report-title');
    output.classList.remove('hidden');
    const month = document.getElementById('fin-month-select').value;

    if (type === 'monthly') {
        title.textContent = 'Monthly Report: ' + month;
        try {
            const res = await fetch(API + '/finance/overview?month=' + month);
            const d = (await res.json()).data;
            content.innerHTML =
                '<div class="grid grid-cols-3 gap-4 mb-4">' +
                '<div class="p-4 bg-green-50 rounded-xl text-center"><p class="text-lg font-bold text-green-700">' + fmt(d.month_income) + '</p><p class="text-xs text-green-600">Mapato</p></div>' +
                '<div class="p-4 bg-red-50 rounded-xl text-center"><p class="text-lg font-bold text-red-700">' + fmt(d.month_expense) + '</p><p class="text-xs text-red-600">Matumizi</p></div>' +
                '<div class="p-4 bg-blue-50 rounded-xl text-center"><p class="text-lg font-bold text-blue-700">' + fmt(d.month_balance) + '</p><p class="text-xs text-blue-600">Salio</p></div></div>' +
                '<h4 class="font-semibold text-sm mb-2">Mgawanyo wa Kategoria</h4>' +
                '<table class="w-full text-sm"><thead><tr class="text-left text-xs text-gray-500 uppercase border-b"><th class="pb-2">Kategoria</th><th class="pb-2">Aina</th><th class="pb-2 text-right">Kiasi</th></tr></thead><tbody>' +
                (d.category_breakdown||[]).map(c =>
                    '<tr class="border-b border-gray-50"><td class="py-2">' + c.name + '</td><td class="py-2"><span class="px-2 py-0.5 rounded text-xs ' + (c.category_type==='income'?'bg-green-100 text-green-800':'bg-red-100 text-red-800') + '">' + c.category_type + '</span></td><td class="py-2 text-right font-medium">' + fmt(c.total) + '</td></tr>'
                ).join('') + '</tbody></table>';
        } catch (e) { content.innerHTML = '<p class="text-red-500">Failed to generate report</p>'; }
    } else if (type === 'category') {
        title.textContent = 'Category Report';
        try {
            const res = await fetch(API + '/finance/overview?month=' + month);
            const d = (await res.json()).data;
            const inc = (d.category_breakdown||[]).filter(c => c.category_type === 'income');
            const exp = (d.category_breakdown||[]).filter(c => c.category_type === 'expense');
            content.innerHTML =
                '<div class="grid grid-cols-2 gap-6">' +
                '<div><h4 class="font-semibold text-green-700 mb-2">Income</h4>' +
                (inc.length ? inc.map(c => '<div class="flex justify-between py-1.5 border-b border-gray-50"><span class="text-sm">' + c.name + '</span><span class="font-medium text-green-600">' + fmt(c.total) + '</span></div>').join('') : '<p class="text-gray-400 text-sm">None</p>') +
                '</div><div><h4 class="font-semibold text-red-700 mb-2">Expenses</h4>' +
                (exp.length ? exp.map(c => '<div class="flex justify-between py-1.5 border-b border-gray-50"><span class="text-sm">' + c.name + '</span><span class="font-medium text-red-600">' + fmt(c.total) + '</span></div>').join('') : '<p class="text-gray-400 text-sm">None</p>') +
                '</div></div>';
        } catch (e) { content.innerHTML = '<p class="text-red-500">Error</p>'; }
    } else if (type === 'pledge') {
        title.textContent = 'Pledge Report';
        try {
            const [pRes, sRes] = await Promise.all([fetch(API + '/finance/pledges'), fetch(API + '/finance/pledges/stats')]);
            const pledges = (await pRes.json()).data || [];
            const stats = (await sRes.json()).data;
            content.innerHTML =
                '<div class="grid grid-cols-4 gap-4 mb-4">' +
                '<div class="p-3 bg-gray-50 rounded-xl text-center"><p class="text-lg font-bold">' + stats.total + '</p><p class="text-xs text-gray-500">Total</p></div>' +
                '<div class="p-3 bg-green-50 rounded-xl text-center"><p class="text-lg font-bold text-green-700">' + fmt(stats.total_pledged) + '</p><p class="text-xs text-green-600">Pledged</p></div>' +
                '<div class="p-3 bg-blue-50 rounded-xl text-center"><p class="text-lg font-bold text-blue-700">' + fmt(stats.total_paid) + '</p><p class="text-xs text-blue-600">Paid</p></div>' +
                '<div class="p-3 bg-amber-50 rounded-xl text-center"><p class="text-lg font-bold text-amber-700">' + fmt(stats.total_balance) + '</p><p class="text-xs text-amber-600">Outstanding</p></div></div>' +
                '<table class="w-full text-sm"><thead><tr class="text-left text-xs text-gray-500 uppercase border-b"><th class="pb-2">Member</th><th class="pb-2">Campaign</th><th class="pb-2 text-right">Pledged</th><th class="pb-2 text-right">Paid</th><th class="pb-2 text-right">Balance</th><th class="pb-2">Status</th></tr></thead><tbody>' +
                pledges.map(p => '<tr class="border-b border-gray-50"><td class="py-2">' + p.first_name + ' ' + p.last_name + '</td><td class="py-2 text-xs">' + (p.campaign||'-') + '</td><td class="py-2 text-right">' + fmt(p.total_amount) + '</td><td class="py-2 text-right text-green-600">' + fmt(p.paid_amount) + '</td><td class="py-2 text-right text-amber-600">' + fmt(p.balance) + '</td><td class="py-2"><span class="px-2 py-0.5 rounded text-xs">' + p.status + '</span></td></tr>').join('') +
                '</tbody></table>';
        } catch (e) { content.innerHTML = '<p class="text-red-500">Error</p>'; }
    }
}

function printReport() {
    const content = document.getElementById('report-output').innerHTML;
    const w = window.open('', '_blank');
    w.document.write('<html><head><title>Finance Report</title><link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"></head><body class="p-8">' + content + '</body></html>');
    w.document.close();
    w.print();
}

// ═══════════ FORM SUBMISSIONS ═══════════
document.getElementById('entry-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const payload = Object.fromEntries(new FormData(this).entries());
    if (!payload.entry_no || payload.entry_no.trim() === '') {
        payload.entry_no = 'FIN-' + new Date().toISOString().slice(0,10).replace(/-/g,'') + '-' + Math.floor(Math.random()*900+100);
    }
    try {
        const res = await fetch(API + '/finance/entries', {
            method: 'POST', headers: {'Content-Type':'application/json'}, body: JSON.stringify(payload)
        });
        const data = await res.json();
        if (res.ok) { this.reset(); closeModal('entry-modal'); loadOverview(); }
        else { alert(data.message || 'Error'); }
    } catch (err) { alert('Network error'); }
});

document.getElementById('pledge-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const payload = Object.fromEntries(new FormData(this).entries());
    try {
        const res = await fetch(API + '/finance/pledges', {
            method: 'POST', headers: {'Content-Type':'application/json'}, body: JSON.stringify(payload)
        });
        const data = await res.json();
        if (res.ok) { this.reset(); closeModal('pledge-modal'); loadPledges(); loadPledgeStats(); }
        else { alert(data.message || 'Error'); }
    } catch (err) { alert('Network error'); }
});

document.getElementById('budget-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const payload = Object.fromEntries(new FormData(this).entries());
    try {
        const res = await fetch(API + '/finance/budgets', {
            method: 'POST', headers: {'Content-Type':'application/json'}, body: JSON.stringify(payload)
        });
        const data = await res.json();
        if (res.ok) { this.reset(); closeModal('budget-modal'); loadBudgets(); }
        else { alert(data.message || 'Error'); }
    } catch (err) { alert('Network error'); }
});

// ═══════════ INIT ═══════════
buildMonthSelector();
loadMeta();
loadOverview();
document.querySelector('.fin-tab').classList.add('border-royal-600','text-royal-700');
</script>

</div>

<style>
.finance-module .fin-tab-active { border-color: #3344a5; color: #3344a5; }
.finance-module .fin-tab { font-family: inherit; }
.finance-module svg { color: inherit; }
</style>
