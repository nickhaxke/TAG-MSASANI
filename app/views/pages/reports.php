<?php $B = $baseUrl ?? ''; ?>

<div class="reports-module">

<!-- ═══════════ HEADER ═══════════ -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-3xl font-heading font-semibold text-royal-900">Financial Reports</h1>
        <p class="text-mist-600 text-sm mt-0.5">Unified budget, expense, and procurement reporting dashboard</p>
    </div>
    <div class="flex items-center gap-2">
        <button onclick="exportCsv()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl shadow-sm transition-colors text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            CSV
        </button>
        <button onclick="exportPdf()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-royal-600 hover:bg-royal-700 text-white font-semibold rounded-xl shadow-sm transition-colors text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z"/></svg>
            PDF
        </button>
    </div>
</div>

<!-- ═══════════ FILTERS ═══════════ -->
<div class="bg-white rounded-2xl shadow-sm border border-mist-100 p-4 mb-6">
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
        <div>
            <label class="block text-xs font-medium text-mist-500 mb-1">From</label>
            <input type="month" id="rpt-date-from" class="w-full border border-mist-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-royal-300">
        </div>
        <div>
            <label class="block text-xs font-medium text-mist-500 mb-1">To</label>
            <input type="month" id="rpt-date-to" class="w-full border border-mist-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-royal-300">
        </div>
        <div>
            <label class="block text-xs font-medium text-mist-500 mb-1">Department</label>
            <select id="rpt-department" class="w-full border border-mist-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-royal-300">
                <option value="">All Departments</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-mist-500 mb-1">Event</label>
            <select id="rpt-event" class="w-full border border-mist-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-royal-300">
                <option value="">All Events</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-mist-500 mb-1">Status</label>
            <select id="rpt-status" class="w-full border border-mist-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-royal-300">
                <option value="">All Statuses</option>
            </select>
        </div>
    </div>
    <div class="mt-3 flex items-center gap-2">
        <button onclick="loadReport()" class="inline-flex items-center gap-2 px-4 py-2 bg-royal-600 hover:bg-royal-700 text-white font-semibold rounded-xl text-sm transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            Apply Filters
        </button>
        <button onclick="clearFilters()" class="px-4 py-2 text-mist-600 hover:text-royal-700 text-sm font-medium transition-colors">
            Clear
        </button>
    </div>
</div>

<!-- ═══════════ KPI CARDS ═══════════ -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Total Budgets -->
    <div class="bg-gradient-to-br from-royal-700 to-royal-900 rounded-2xl p-5 text-white shadow-md">
        <div class="flex items-start justify-between mb-3">
            <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <span class="text-[10px] font-semibold bg-white/20 rounded-full px-2 py-0.5" id="kpi-budget-count">0</span>
        </div>
        <p class="text-xl font-extrabold leading-tight" id="kpi-total-requested">TZS 0</p>
        <p class="text-xs text-blue-200 mt-1 font-medium">Budget Requested</p>
    </div>

    <!-- Approved -->
    <div class="bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-2xl p-5 text-white shadow-md">
        <div class="flex items-start justify-between mb-3">
            <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <p class="text-xl font-extrabold leading-tight" id="kpi-total-approved">TZS 0</p>
        <p class="text-xs text-emerald-100 mt-1 font-medium">Budget Approved</p>
    </div>

    <!-- Total Expenses -->
    <div class="bg-gradient-to-br from-red-500 to-rose-700 rounded-2xl p-5 text-white shadow-md">
        <div class="flex items-start justify-between mb-3">
            <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15"/></svg>
            </div>
        </div>
        <p class="text-xl font-extrabold leading-tight" id="kpi-total-expenses">TZS 0</p>
        <p class="text-xs text-red-100 mt-1 font-medium">Total Expenses</p>
    </div>

    <!-- Remaining Balance -->
    <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl p-5 text-white shadow-md">
        <div class="flex items-start justify-between mb-3">
            <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            </div>
        </div>
        <p class="text-xl font-extrabold leading-tight" id="kpi-total-remaining">TZS 0</p>
        <p class="text-xs text-amber-100 mt-1 font-medium">Remaining Balance</p>
    </div>
</div>

<!-- ═══════════ SECONDARY KPIs (Finance + Procurement) ═══════════ -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Income -->
    <div class="bg-white rounded-2xl shadow-sm border border-mist-100 p-4">
        <p class="text-xs font-medium text-mist-500 mb-1">Finance Income</p>
        <p class="text-lg font-bold text-emerald-700" id="kpi-finance-income">TZS 0</p>
    </div>
    <!-- Expense -->
    <div class="bg-white rounded-2xl shadow-sm border border-mist-100 p-4">
        <p class="text-xs font-medium text-mist-500 mb-1">Finance Expense</p>
        <p class="text-lg font-bold text-red-600" id="kpi-finance-expense">TZS 0</p>
    </div>
    <!-- Procurement Requests -->
    <div class="bg-white rounded-2xl shadow-sm border border-mist-100 p-4">
        <p class="text-xs font-medium text-mist-500 mb-1">Procurement Requests</p>
        <p class="text-lg font-bold text-royal-700" id="kpi-pr-count">0</p>
    </div>
    <!-- Procurement Value -->
    <div class="bg-white rounded-2xl shadow-sm border border-mist-100 p-4">
        <p class="text-xs font-medium text-mist-500 mb-1">Procurement Value</p>
        <p class="text-lg font-bold text-royal-700" id="kpi-pr-amount">TZS 0</p>
    </div>
</div>

<!-- ═══════════ STATUS BREAKDOWN ═══════════ -->
<div class="flex flex-wrap gap-2 mb-6" id="status-badges"></div>

<!-- ═══════════ TREND CHART + TABLE ═══════════ -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">
    <!-- Chart -->
    <div class="bg-white rounded-2xl shadow-sm border border-mist-100 p-5 xl:col-span-1">
        <h3 class="text-sm font-semibold text-gray-800 mb-3">Monthly Trend (Last 6 Months)</h3>
        <div class="relative" style="height: 280px;">
            <canvas id="rpt-trend-chart"></canvas>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-mist-100 p-5 xl:col-span-2 overflow-hidden">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-semibold text-gray-800">Budget Details</h3>
            <span class="text-xs text-mist-500" id="rpt-row-count">0 records</span>
        </div>
        <div class="overflow-x-auto -mx-5 px-5">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b border-mist-200 text-left">
                        <th class="py-2 pr-3 font-semibold text-mist-600 text-xs whitespace-nowrap">Department</th>
                        <th class="py-2 pr-3 font-semibold text-mist-600 text-xs whitespace-nowrap">Event</th>
                        <th class="py-2 pr-3 font-semibold text-mist-600 text-xs whitespace-nowrap">Month</th>
                        <th class="py-2 pr-3 font-semibold text-mist-600 text-xs whitespace-nowrap text-right">Requested</th>
                        <th class="py-2 pr-3 font-semibold text-mist-600 text-xs whitespace-nowrap text-right">Approved</th>
                        <th class="py-2 pr-3 font-semibold text-mist-600 text-xs whitespace-nowrap text-right">Expenses</th>
                        <th class="py-2 pr-3 font-semibold text-mist-600 text-xs whitespace-nowrap text-right">Remaining</th>
                        <th class="py-2 pr-3 font-semibold text-mist-600 text-xs whitespace-nowrap text-center">PRs</th>
                        <th class="py-2 font-semibold text-mist-600 text-xs whitespace-nowrap">Status</th>
                    </tr>
                </thead>
                <tbody id="rpt-table-body" class="divide-y divide-mist-100">
                    <tr><td colspan="9" class="py-12 text-center text-mist-400 text-sm">Loading...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

</div><!-- /.reports-module -->

<!-- ═══════════ PRINT STYLES ═══════════ -->
<style>
@media print {
    nav, header, .sidebar, button, select, input, label,
    .no-print { display: none !important; }
    .reports-module { padding: 0; }
    .rounded-2xl { border-radius: 0 !important; }
    .shadow-sm, .shadow-md { box-shadow: none !important; }
}
</style>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>

<script>
const RPT_API = BASE_URL + '/api/v1';
let rptTrendChart = null;

function fmt(n) {
    return 'TZS ' + Number(n || 0).toLocaleString('en-US', { minimumFractionDigits: 0 });
}
function fmtShort(n) {
    n = Number(n || 0);
    if (n >= 1e6) return 'TZS ' + (n / 1e6).toFixed(1) + 'M';
    if (n >= 1e3) return 'TZS ' + (n / 1e3).toFixed(0) + 'K';
    return 'TZS ' + n.toLocaleString('en-US');
}

/* ── Collect current filter values ── */
function getFilterParams() {
    const p = new URLSearchParams();
    const df = document.getElementById('rpt-date-from').value;
    const dt = document.getElementById('rpt-date-to').value;
    const dept = document.getElementById('rpt-department').value;
    const evt = document.getElementById('rpt-event').value;
    const st = document.getElementById('rpt-status').value;
    if (df) p.set('date_from', df);
    if (dt) p.set('date_to', dt);
    if (dept) p.set('department', dept);
    if (evt) p.set('event_id', evt);
    if (st) p.set('status', st);
    return p;
}

/* ── Load report data ── */
async function loadReport() {
    const params = getFilterParams();
    try {
        const res = await fetch(`${RPT_API}/reports/dashboard?${params}`);
        const json = await res.json();
        if (!json.success) { console.error(json.message); return; }
        const d = json.data;
        renderKPIs(d.kpi, d.finance_summary);
        renderStatusBadges(d.kpi.by_status);
        renderTable(d.rows);
        renderTrendChart(d.trend);
        populateFilters(d.filters);
    } catch (e) {
        console.error('Report load error:', e);
    }
}

/* ── Populate filter dropdowns (preserving current selections) ── */
function populateFilters(filters) {
    fillSelect('rpt-department', filters.departments.map(d => ({ value: d, label: d })), 'All Departments');
    fillSelect('rpt-event', filters.events.map(e => ({ value: e.id, label: e.title })), 'All Events');
    fillSelect('rpt-status', filters.statuses.map(s => ({ value: s, label: s.charAt(0).toUpperCase() + s.slice(1).replace(/_/g, ' ') })), 'All Statuses');
}

function fillSelect(id, options, placeholder) {
    const el = document.getElementById(id);
    const cur = el.value;
    el.innerHTML = `<option value="">${placeholder}</option>` +
        options.map(o => `<option value="${o.value}"${String(o.value) === cur ? ' selected' : ''}>${o.label}</option>`).join('');
}

/* ── KPI Cards ── */
function renderKPIs(kpi, fin) {
    document.getElementById('kpi-budget-count').textContent = kpi.total_budgets;
    document.getElementById('kpi-total-requested').textContent = fmt(kpi.total_requested);
    document.getElementById('kpi-total-approved').textContent = fmt(kpi.total_approved);
    document.getElementById('kpi-total-expenses').textContent = fmt(kpi.total_expenses);
    document.getElementById('kpi-total-remaining').textContent = fmt(kpi.total_remaining);
    document.getElementById('kpi-finance-income').textContent = fmt(fin?.total_income);
    document.getElementById('kpi-finance-expense').textContent = fmt(fin?.total_expense);
    document.getElementById('kpi-pr-count').textContent = kpi.total_pr_count;
    document.getElementById('kpi-pr-amount').textContent = fmt(kpi.total_pr_amount);
}

/* ── Status Badges ── */
function renderStatusBadges(byStatus) {
    const colors = {
        submitted: 'bg-blue-100 text-blue-800',
        approved: 'bg-emerald-100 text-emerald-800',
        expenses_added: 'bg-purple-100 text-purple-800',
        closed: 'bg-gray-200 text-gray-700',
        rejected: 'bg-red-100 text-red-800',
    };
    const el = document.getElementById('status-badges');
    el.innerHTML = Object.entries(byStatus).map(([s, c]) => {
        const cls = colors[s] || 'bg-gray-100 text-gray-700';
        const label = s.charAt(0).toUpperCase() + s.slice(1).replace(/_/g, ' ');
        return `<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold ${cls}">
            ${label} <b>${c}</b>
        </span>`;
    }).join('');
}

/* ── Data Table ── */
function renderTable(rows) {
    document.getElementById('rpt-row-count').textContent = rows.length + ' record' + (rows.length !== 1 ? 's' : '');
    const tbody = document.getElementById('rpt-table-body');
    if (!rows.length) {
        tbody.innerHTML = '<tr><td colspan="9" class="py-12 text-center text-mist-400 text-sm">No data matching current filters</td></tr>';
        return;
    }
    const statusCls = {
        submitted: 'bg-blue-100 text-blue-800',
        approved: 'bg-emerald-100 text-emerald-800',
        expenses_added: 'bg-purple-100 text-purple-800',
        closed: 'bg-gray-200 text-gray-700',
        rejected: 'bg-red-100 text-red-800',
    };
    tbody.innerHTML = rows.map(r => {
        const cls = statusCls[r.status] || 'bg-gray-100 text-gray-700';
        const label = r.status.charAt(0).toUpperCase() + r.status.slice(1).replace(/_/g, ' ');
        const remaining = Number(r.remaining_balance);
        const remCls = remaining < 0 ? 'text-red-600 font-semibold' : '';
        return `<tr class="hover:bg-mist-50 transition-colors">
            <td class="py-2.5 pr-3 whitespace-nowrap font-medium text-gray-900">${esc(r.department)}</td>
            <td class="py-2.5 pr-3 whitespace-nowrap text-mist-600">${esc(r.event_name || '—')}</td>
            <td class="py-2.5 pr-3 whitespace-nowrap text-mist-600">${esc(r.fiscal_month)}</td>
            <td class="py-2.5 pr-3 whitespace-nowrap text-right">${fmtShort(r.budget_requested)}</td>
            <td class="py-2.5 pr-3 whitespace-nowrap text-right">${fmtShort(r.budget_approved)}</td>
            <td class="py-2.5 pr-3 whitespace-nowrap text-right">${fmtShort(r.total_expenses)}</td>
            <td class="py-2.5 pr-3 whitespace-nowrap text-right ${remCls}">${fmtShort(remaining)}</td>
            <td class="py-2.5 pr-3 whitespace-nowrap text-center">${r.pr_count}</td>
            <td class="py-2.5"><span class="px-2 py-0.5 rounded-full text-xs font-semibold ${cls}">${label}</span></td>
        </tr>`;
    }).join('');
}

function esc(s) {
    const d = document.createElement('div');
    d.textContent = s ?? '';
    return d.innerHTML;
}

/* ── Trend Chart ── */
function renderTrendChart(trend) {
    const ctx = document.getElementById('rpt-trend-chart');
    if (rptTrendChart) rptTrendChart.destroy();
    const labels = trend.map(t => t.fiscal_month);
    rptTrendChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [
                { label: 'Approved', data: trend.map(t => +t.approved), backgroundColor: 'rgba(16,185,129,0.7)', borderRadius: 6 },
                { label: 'Spent',    data: trend.map(t => +t.spent),    backgroundColor: 'rgba(239,68,68,0.7)',   borderRadius: 6 },
                { label: 'Reserved', data: trend.map(t => +t.reserved), backgroundColor: 'rgba(245,158,11,0.5)', borderRadius: 6 },
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, padding: 12, font: { size: 11 } } } },
            scales: {
                x: { grid: { display: false } },
                y: { beginAtZero: true, ticks: { callback: v => v >= 1e6 ? (v/1e6).toFixed(1)+'M' : v >= 1e3 ? (v/1e3).toFixed(0)+'K' : v } }
            }
        }
    });
}

/* ── Export CSV ── */
function exportCsv() {
    const params = getFilterParams();
    window.location.href = `${RPT_API}/reports/export/csv?${params}`;
}

/* ── Export PDF (Print) ── */
function exportPdf() {
    window.print();
}

/* ── Clear Filters ── */
function clearFilters() {
    document.getElementById('rpt-date-from').value = '';
    document.getElementById('rpt-date-to').value = '';
    document.getElementById('rpt-department').value = '';
    document.getElementById('rpt-event').value = '';
    document.getElementById('rpt-status').value = '';
    loadReport();
}

/* ── Init ── */
document.addEventListener('DOMContentLoaded', () => loadReport());
</script>
