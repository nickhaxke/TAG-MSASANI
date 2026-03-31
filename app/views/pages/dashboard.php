<?php
$B = $baseUrl ?? '';
$monthlyIncomeServer = (float) ($stats['income'] ?? 0);
$statMembers  = (int) ($stats['members'] ?? 0);
$statGroups   = (int) ($stats['groups'] ?? 0);
$statEvents   = (int) ($stats['events'] ?? 0);
$statExpenses = (float) ($stats['expenses'] ?? 0);
?>

<style>
@media (min-width: 1280px) {
    .db-root { height: calc(100vh - 80px); overflow: hidden; }
}
.cal-cell { transition: box-shadow 0.15s; cursor: pointer; }
.cal-cell:hover { box-shadow: 0 2px 8px rgba(79,54,216,0.10); }
</style>

<div class="db-root flex flex-col xl:overflow-hidden">
    <!-- Main 50/50 grid -->
    <div id="dashboard-grid" class="flex-1 min-h-0 grid grid-cols-1 xl:grid-cols-2 gap-4 xl:overflow-hidden">

        <!-- LEFT: Calendar -->
        <div id="dashboard-calendar-panel" class="min-h-0 bg-white rounded-2xl border border-mist-200 shadow-sm p-4 flex flex-col">

            <!-- Calendar header -->
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-lg font-heading font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-royal-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Church Calendar
                </h2>
                <div class="flex items-center gap-1">
                    <button id="cal-prev" class="w-8 h-8 rounded-lg border border-gray-200 hover:bg-gray-50 text-gray-500 hover:text-gray-700 flex items-center justify-center transition-colors">◀</button>
                    <div id="month-label" class="px-4 py-1.5 rounded-lg bg-royal-50 text-sm font-bold text-royal-800 min-w-[140px] text-center"></div>
                    <button id="cal-next" class="w-8 h-8 rounded-lg border border-gray-200 hover:bg-gray-50 text-gray-500 hover:text-gray-700 flex items-center justify-center transition-colors">▶</button>
                    <button id="cal-today" class="ml-2 px-4 py-1.5 rounded-lg bg-royal-600 text-white hover:bg-royal-700 text-sm font-semibold shadow-sm transition-colors">Today</button>
                </div>
            </div>

            <!-- Filters -->
            <div class="flex items-center gap-2 mb-3">
                <select id="filter-kind" class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-600 bg-white">
                    <option value="">All Types</option>
                    <option value="worship">Worship</option>
                    <option value="youth">Youth</option>
                    <option value="appointment">Appointment</option>
                    <option value="special">Special</option>
                </select>
                <select id="filter-group" class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-600 bg-white">
                    <option value="">All Groups</option>
                </select>
            </div>

            <!-- Day headers -->
            <div class="grid grid-cols-7 gap-1 mb-1">
                <div class="text-center py-1 text-xs font-bold text-amber-600 bg-amber-50 rounded">SUN</div>
                <div class="text-center py-1 text-xs font-bold text-gray-500 bg-gray-50 rounded">MON</div>
                <div class="text-center py-1 text-xs font-bold text-gray-500 bg-gray-50 rounded">TUE</div>
                <div class="text-center py-1 text-xs font-bold text-gray-500 bg-gray-50 rounded">WED</div>
                <div class="text-center py-1 text-xs font-bold text-gray-500 bg-gray-50 rounded">THU</div>
                <div class="text-center py-1 text-xs font-bold text-gray-500 bg-gray-50 rounded">FRI</div>
                <div class="text-center py-1 text-xs font-bold text-gray-500 bg-gray-50 rounded">SAT</div>
            </div>

            <!-- Calendar grid -->
            <div id="calendar-grid-wrap" class="flex-1 min-h-0 overflow-hidden">
                <div id="calendar-grid" class="grid grid-cols-7 gap-1 h-full"></div>
            </div>

            <!-- Legend -->
            <div class="flex items-center gap-4 mt-2 pt-2 border-t border-gray-100">
                <span class="inline-flex items-center gap-1.5 text-xs text-gray-500"><span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>Worship</span>
                <span class="inline-flex items-center gap-1.5 text-xs text-gray-500"><span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>Youth</span>
                <span class="inline-flex items-center gap-1.5 text-xs text-gray-500"><span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>Appointment</span>
                <span class="inline-flex items-center gap-1.5 text-xs text-gray-500"><span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span>Special</span>
                <span class="inline-flex items-center gap-1.5 text-xs text-gray-500"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>Liturgical</span>
            </div>
        </div>

        <!-- RIGHT: Insights -->
        <aside id="dashboard-insights" class="min-h-0 flex flex-col gap-3 xl:overflow-hidden">

            <!-- Card 1: Upcoming Events -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm px-3 py-2 flex-none flex flex-col">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-5 h-5 rounded-md bg-blue-50 flex items-center justify-center">
                        <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-xs font-heading font-bold text-gray-800">Upcoming Events</h3>
                </div>
                <!-- fixed height = 1 row visible, scroll for more -->
                <div id="upcoming-list" class="overflow-y-auto space-y-1" style="max-height:32px"></div>
            </div>

            <!-- Card 2: Last Sunday Summary -->
            <div class="bg-gradient-to-br from-royal-700 to-royal-800 rounded-2xl p-4 text-white shadow-md flex-1 min-h-0">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-base font-heading font-bold">Last Sunday</h3>
                    <p id="last-sunday-date" class="text-xs text-white/70"></p>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-white/15 rounded-xl px-3 py-2.5">
                        <p class="text-[11px] uppercase tracking-wider text-white/60 font-semibold">Attendance</p>
                        <p id="last-sunday-attendance" class="text-2xl font-bold leading-tight mt-1">0</p>
                    </div>
                    <div class="bg-white/15 rounded-xl px-3 py-2.5">
                        <p class="text-[11px] uppercase tracking-wider text-white/60 font-semibold">Offering</p>
                        <p id="last-sunday-offering" class="text-lg font-bold leading-tight mt-1">TZS 0</p>
                    </div>
                </div>
                <p id="last-sunday-trend" class="text-xs mt-2 text-white/70 font-medium"></p>
            </div>

            <!-- Row: Highlights + Finance side by side -->
            <div class="grid grid-cols-2 gap-3 flex-1 min-h-0">

                <!-- Church Overview -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 flex flex-col min-h-0">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center">
                            <svg class="w-4.5 h-4.5 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <h3 class="text-sm font-heading font-bold text-gray-800">Church Overview</h3>
                    </div>
                    <div class="grid grid-cols-2 gap-2 flex-1">
                        <div class="rounded-xl bg-blue-50 px-2.5 py-2 text-center">
                            <p class="text-xl font-bold text-blue-700"><?= $statMembers ?></p>
                            <p class="text-[10px] text-blue-500 font-semibold uppercase">Members</p>
                        </div>
                        <div class="rounded-xl bg-purple-50 px-2.5 py-2 text-center">
                            <p class="text-xl font-bold text-purple-700"><?= $statGroups ?></p>
                            <p class="text-[10px] text-purple-500 font-semibold uppercase">Groups</p>
                        </div>
                        <div class="rounded-xl bg-amber-50 px-2.5 py-2 text-center">
                            <p class="text-xl font-bold text-amber-700"><?= $statEvents ?></p>
                            <p class="text-[10px] text-amber-500 font-semibold uppercase">Events</p>
                        </div>
                        <div class="rounded-xl bg-rose-50 px-2.5 py-2 text-center">
                            <p class="text-lg font-bold text-rose-700"><?= number_format($statExpenses, 0) ?></p>
                            <p class="text-[10px] text-rose-500 font-semibold uppercase">Expenses</p>
                        </div>
                    </div>
                </div>

                <!-- Finance -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 flex flex-col min-h-0">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                            <svg class="w-4.5 h-4.5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="text-sm font-heading font-bold text-gray-800">Finance</h3>
                    </div>
                    <div class="space-y-2 flex-1">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Last Sunday</span>
                            <span id="finance-last-sunday" class="text-sm font-bold text-gray-800">TZS 0</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">This Week</span>
                            <span id="finance-weekly" class="text-sm font-bold text-gray-800">TZS 0</span>
                        </div>
                        <div class="flex items-center justify-between pt-1 border-t border-gray-100">
                            <span class="text-sm text-gray-600 font-semibold">Monthly</span>
                            <span id="finance-monthly" class="text-sm font-bold text-emerald-600">TZS 0</span>
                        </div>
                    </div>
                </div>

            </div>
        </aside>
    </div>
</div>

<div id="quick-create-modal" class="hidden fixed inset-0 z-50 bg-black/50 p-4">
    <div class="max-w-xl mx-auto bg-white rounded-2xl shadow-2xl p-6 mt-10 modal-enter">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-2xl font-heading font-semibold text-royal-800">Create Event</h3>
            <button id="close-quick-modal" class="text-mist-600 hover:text-mist-900">✕</button>
        </div>
        <form id="quick-event-form" class="space-y-3">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <input name="title" required placeholder="Event name" class="rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                <select name="event_type" required class="rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                    <option value="">Type</option>
                    <option value="service">Service</option>
                    <option value="seminar">Seminar</option>
                    <option value="meeting">Meeting</option>
                    <option value="appointment">Appointment</option>
                </select>
                <input id="quick-date" name="date" type="date" required class="rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                <input name="time" type="time" required class="rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                <input name="location" placeholder="Location" class="rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                <input name="budget" type="number" min="0" step="0.01" placeholder="Budget (planned cost)" class="rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                <input name="expected_attendance" type="number" min="0" placeholder="Expected attendance" class="rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                <select id="quick-organizer" name="organizer_user_id" class="rounded-xl border border-mist-200 px-3 py-2.5 text-sm"><option value="">Organizer (person)</option></select>
                <select id="quick-group" name="target_group_id" class="rounded-xl border border-mist-200 px-3 py-2.5 text-sm"><option value="">Organizer group</option></select>
                <input name="duration_hours" type="number" value="2" min="1" max="24" step="1" placeholder="Duration hours" class="rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
            </div>
            <textarea name="description" rows="3" placeholder="Description" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm"></textarea>
            <div class="flex items-center gap-4 text-sm">
                <label class="inline-flex items-center gap-2"><input type="checkbox" name="send_email" value="1">Email notification</label>
                <label class="inline-flex items-center gap-2"><input type="checkbox" name="send_sms" value="1">SMS notification</label>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" id="cancel-quick-modal" class="px-4 py-2.5 rounded-xl border border-mist-200 text-mist-700">Cancel</button>
                <button type="submit" class="px-4 py-2.5 rounded-xl bg-royal-600 text-white hover:bg-royal-700">Create Event</button>
            </div>
        </form>
    </div>
</div>

<div id="sunday-modal" class="hidden fixed inset-0 z-50 bg-black/50 p-4">
    <div class="max-w-md mx-auto bg-white rounded-2xl shadow-2xl p-6 mt-20">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-heading font-semibold text-royal-800">Sunday Snapshot</h3>
            <button id="close-sunday-modal" class="text-mist-600 hover:text-mist-900">✕</button>
        </div>
        <p id="sunday-modal-date" class="text-sm text-mist-600 mb-4"></p>
        <div class="grid grid-cols-2 gap-3">
            <div class="rounded-xl bg-mist-50 border border-mist-200 p-4">
                <p class="text-xs uppercase tracking-wide text-mist-500">Attendance</p>
                <p id="sunday-modal-attendance" class="text-2xl font-bold text-royal-800 mt-1">0</p>
            </div>
            <div class="rounded-xl bg-glory-50 border border-glory-200 p-4">
                <p class="text-xs uppercase tracking-wide text-glory-700">Offering</p>
                <p id="sunday-modal-offering" class="text-xl font-bold text-royal-900 mt-1">TZS 0</p>
            </div>
        </div>
    </div>
</div>

<div id="event-preview" class="hidden fixed z-50 rounded-xl border border-mist-200 bg-white/95 backdrop-blur shadow-xl p-3 max-w-xs text-xs"></div>

<script>
const SERVER_MONTHLY_INCOME = <?= json_encode($monthlyIncomeServer) ?>;

const calendarState = {
    current: new Date(),
    events: [],
    groups: [],
    ui: {
        cellHeight: 62,
        maxUpcoming: 5,
    },
    insights: {
        upcoming: [],
        sunday_summaries: {},
        last_sunday: { date: '', attendance: 0, offering: 0, trend: 'flat' },
        highlights: { next_sunday_focus: null, special_upcoming: null },
        financial_snapshot: { last_sunday_offering: 0, weekly_total: 0, monthly_income: SERVER_MONTHLY_INCOME }
    },
    filterKind: '',
    filterGroup: ''
};

const kindMeta = {
    worship: { cls: 'bg-blue-500 text-white', tag: 'Worship' },
    youth: { cls: 'bg-amber-500 text-white', tag: 'Youth' },
    appointment: { cls: 'bg-rose-500 text-white', tag: 'Appointment' },
    special: { cls: 'bg-purple-500 text-white', tag: 'Special' },
};

function ymd(date) {
    return date.toISOString().slice(0, 10);
}

function monthKey(date) {
    return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}`;
}

function formatTzs(amount) {
    return `TZS ${Number(amount || 0).toLocaleString('en-US', { maximumFractionDigits: 0 })}`;
}

function openQuickModal(dateStr) {
    document.getElementById('quick-date').value = dateStr;
    document.getElementById('quick-create-modal').classList.remove('hidden');
}

function closeQuickModal() {
    document.getElementById('quick-create-modal').classList.add('hidden');
}

function openSundayModal(dateStr) {
    const stats = calendarState.insights.sunday_summaries?.[dateStr] || { attendance: 0, offering: 0 };
    document.getElementById('sunday-modal-date').textContent = new Date(`${dateStr}T00:00:00`).toLocaleDateString([], { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' });
    document.getElementById('sunday-modal-attendance').textContent = Number(stats.attendance || 0).toLocaleString();
    document.getElementById('sunday-modal-offering').textContent = formatTzs(stats.offering || 0);
    document.getElementById('sunday-modal').classList.remove('hidden');
}

function closeSundayModal() {
    document.getElementById('sunday-modal').classList.add('hidden');
}

function filteredEvents() {
    return calendarState.events.filter((event) => {
        if (calendarState.filterKind && event.kind !== calendarState.filterKind) return false;
        if (calendarState.filterGroup && String(event.target_group_id || '') !== calendarState.filterGroup) return false;
        return true;
    });
}

function currentWeekRange() {
    const today = new Date();
    const day = today.getDay();
    const start = new Date(today);
    start.setDate(today.getDate() - day);
    start.setHours(0, 0, 0, 0);
    const end = new Date(start);
    end.setDate(start.getDate() + 6);
    end.setHours(23, 59, 59, 999);
    return { start, end };
}

function renderCalendar() {
    const grid = document.getElementById('calendar-grid');
    const label = document.getElementById('month-label');
    const date = new Date(calendarState.current.getFullYear(), calendarState.current.getMonth(), 1);
    const startDay = date.getDay();
    const daysInMonth = new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate();
    const todayKey = ymd(new Date());
    const weekRange = currentWeekRange();

    label.textContent = date.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });

    const byDate = {};
    filteredEvents().forEach((e) => {
        const key = String(e.start_datetime).slice(0, 10);
        if (!byDate[key]) byDate[key] = [];
        byDate[key].push(e);
    });

    const totalCells = startDay + daysInMonth;
    const rows = Math.ceil(totalCells / 7);
    grid.style.gridTemplateRows = `repeat(${rows}, 1fr)`;

    const cells = [];
    for (let i = 0; i < startDay; i++) {
        cells.push(`<div class="rounded-lg bg-gray-50/50"></div>`);
    }

    for (let day = 1; day <= daysInMonth; day++) {
        const d = new Date(date.getFullYear(), date.getMonth(), day);
        const key = ymd(d);
        const events = byDate[key] || [];
        const isToday = key === todayKey;
        const isSunday = d.getDay() === 0;
        const inCurrentWeek = d >= weekRange.start && d <= weekRange.end;

        const eventHtml = events.slice(0, 1).map((e) => {
            const system = Boolean(e.is_system);
            const kind = system ? 'special' : (e.kind || 'special');
            const cls = system ? 'bg-emerald-500 text-white' : (kindMeta[kind]?.cls || kindMeta.special.cls);
            const time = new Date(e.start_datetime).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            const tag = e.tag || kindMeta[kind]?.tag || 'Special';
            const apptWithMatch = (kind === 'appointment' && e.notes) ? String(e.notes).match(/\[appointment_with:([^\]]+)\]/i) : null;
            const apptWith = apptWithMatch ? apptWithMatch[1].trim() : '';
            const pillLabel = (kind === 'appointment' && apptWith) ? `\u{1F4C5} ${apptWith}` : e.title;
            const previewExtra = apptWith ? ` \u2014 with ${apptWith}` : '';
            const preview = encodeURIComponent(`${e.title}||${time}||${e.venue || 'Main Sanctuary'}||${tag}${previewExtra}${system ? ' (System)' : ''}`);
            return `<button class="w-full text-left text-[10px] leading-tight px-1.5 py-0.5 rounded ${cls} truncate event-pill" data-event-id="${e.id}" data-system="${system ? '1' : '0'}" data-preview="${preview}">${pillLabel}</button>`;
        }).join('');

        const more = events.length > 1 ? `<div class="text-[10px] font-medium text-gray-400">+${events.length - 1}</div>` : '';

        let cellBg = 'bg-white border-gray-200';
        if (isToday) cellBg = 'bg-royal-50 border-royal-300 ring-2 ring-royal-400/50';
        else if (isSunday) cellBg = 'bg-amber-50/50 border-amber-200';
        else if (inCurrentWeek) cellBg = 'bg-blue-50/30 border-blue-100';

        cells.push(`
            <div class="cal-cell rounded-lg border ${cellBg} px-1 py-0.5 overflow-hidden">
                <div class="text-xs font-bold ${isToday ? 'text-royal-700' : (isSunday ? 'text-amber-700' : 'text-gray-700')} day-button cursor-pointer" data-date="${key}" data-sunday="${isSunday ? '1' : '0'}">${day}</div>
                <div class="mt-0.5">${eventHtml}${more}</div>
            </div>
        `);
    }

    grid.innerHTML = cells.join('');

    document.querySelectorAll('.day-button').forEach((btn) => {
        btn.addEventListener('click', () => {
            if (btn.dataset.sunday === '1') {
                openSundayModal(btn.dataset.date);
                return;
            }
            openQuickModal(btn.dataset.date);
        });
    });

    document.querySelectorAll('.cal-cell').forEach((cell) => {
        const btn = cell.querySelector('.day-button');
        if (btn) cell.addEventListener('click', (e) => { if (e.target === cell) btn.click(); });
    });

    const previewBox = document.getElementById('event-preview');
    document.querySelectorAll('.event-pill').forEach((btn) => {
        btn.addEventListener('mouseenter', (ev) => {
            const [title, time, venue, tag] = decodeURIComponent(btn.dataset.preview || '').split('||');
            previewBox.innerHTML = `<p class="font-semibold text-royal-900">${title || ''}</p><p class="text-mist-600 mt-1">${time || ''}</p><p class="text-mist-600">${venue || ''}</p><p class="text-glory-700 mt-1">${tag || ''}</p>`;
            previewBox.classList.remove('hidden');
            previewBox.style.left = `${ev.clientX + 14}px`;
            previewBox.style.top = `${ev.clientY + 14}px`;
        });
        btn.addEventListener('mousemove', (ev) => {
            previewBox.style.left = `${ev.clientX + 14}px`;
            previewBox.style.top = `${ev.clientY + 14}px`;
        });
        btn.addEventListener('mouseleave', () => {
            previewBox.classList.add('hidden');
        });
        btn.addEventListener('click', () => {
            if (btn.dataset.system === '1') return;
            window.location.href = `${BASE_URL}/events/${btn.dataset.eventId}`;
        });
    });

    renderUpcoming();
    renderLastSunday();
    renderFinancialSnapshot();
}

function renderUpcoming() {
    // Show all upcoming events but only 1 row is visible — user scrolls for more
    const list = (calendarState.insights.upcoming || []).slice(0, 10);
    const box = document.getElementById('upcoming-list');
    if (list.length === 0) {
        box.innerHTML = '<p class="text-gray-400 text-sm">No upcoming events.</p>';
        return;
    }

    box.innerHTML = list.map((e) => {
        const dt = new Date(e.start_datetime);
        const dateStr = dt.toLocaleDateString([], { month: 'short', day: 'numeric' });
        const timeStr = dt.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        const system = Boolean(e.is_system);
        const accent = system ? 'border-l-emerald-500 bg-emerald-50/30' : 'border-l-blue-500';
        return `
            <div class="flex items-center gap-2 rounded-lg border border-gray-100 ${accent} border-l-[3px] px-2 py-1 hover:bg-gray-50 transition-colors">
                <span class="flex-shrink-0 text-xs font-bold text-gray-400 uppercase">${dateStr}</span>
                <span class="flex-1 min-w-0 text-sm font-semibold text-gray-800 truncate">${e.title}</span>
                <span class="flex-shrink-0 text-xs text-gray-400">${timeStr}</span>
            </div>
        `;
    }).join('');
}

function fitDashboardViewport() {
    const footer = document.getElementById('app-footer');
    const main = document.getElementById('main-content');
    if (!main) return;

    if (window.innerWidth >= 1280) {
        if (footer) footer.style.display = 'none';
        main.style.padding = '12px 20px 8px';
    } else {
        if (footer) footer.style.display = '';
        main.style.padding = '';
        calendarState.ui.maxUpcoming = 5;
    }
}

function renderLastSunday() {
    const item = calendarState.insights.last_sunday || { date: '', attendance: 0, offering: 0, trend: 'flat' };
    const trendIcon = item.trend === 'up' ? '↑' : (item.trend === 'down' ? '↓' : '→');
    const trendText = item.trend === 'up' ? `${trendIcon} Trending up` : (item.trend === 'down' ? `${trendIcon} Trending down` : `${trendIcon} Stable`);
    document.getElementById('last-sunday-date').textContent = item.date || '-';
    document.getElementById('last-sunday-attendance').textContent = Number(item.attendance || 0).toLocaleString();
    document.getElementById('last-sunday-offering').textContent = formatTzs(item.offering || 0);
    document.getElementById('last-sunday-trend').textContent = trendText;
}

function renderFinancialSnapshot() {
    const fs = calendarState.insights.financial_snapshot || {};
    document.getElementById('finance-last-sunday').textContent = formatTzs(fs.last_sunday_offering || 0);
    document.getElementById('finance-weekly').textContent = formatTzs(fs.weekly_total || 0);
    document.getElementById('finance-monthly').textContent = formatTzs(fs.monthly_income ?? SERVER_MONTHLY_INCOME);
}

async function loadGroups() {
    const res = await fetch(BASE_URL + '/api/v1/meta/groups');
    const payload = await res.json();
    calendarState.groups = payload.data || [];
    const selects = [document.getElementById('filter-group'), document.getElementById('quick-group')];
    selects.forEach((sel, idx) => {
        const firstLabel = idx === 0 ? 'All Groups' : 'Organizer group';
        sel.innerHTML = `<option value="">${firstLabel}</option>` + calendarState.groups.map((g) => `<option value="${g.id}">${g.name}</option>`).join('');
    });
}

async function loadUsers() {
    const res = await fetch(BASE_URL + '/api/v1/meta/users');
    const payload = await res.json();
    const users = payload.data || [];
    const sel = document.getElementById('quick-organizer');
    sel.innerHTML = '<option value="">Organizer (person)</option>' + users.map((u) => `<option value="${u.id}">${u.full_name}</option>`).join('');
}

async function loadMonth() {
    fitDashboardViewport();
    const month = monthKey(calendarState.current);
    const [eventRes, insightRes] = await Promise.all([
        fetch(BASE_URL + '/api/v1/events/calendar?month=' + month),
        fetch(BASE_URL + '/api/v1/dashboard/insights?month=' + month),
    ]);

    const eventPayload = await eventRes.json();
    const insightPayload = await insightRes.json();

    calendarState.events = eventPayload.data?.events || [];
    calendarState.insights = insightPayload.data || calendarState.insights;
    renderCalendar();
}

document.getElementById('cal-prev').addEventListener('click', async () => {
    calendarState.current = new Date(calendarState.current.getFullYear(), calendarState.current.getMonth() - 1, 1);
    await loadMonth();
});

document.getElementById('cal-next').addEventListener('click', async () => {
    calendarState.current = new Date(calendarState.current.getFullYear(), calendarState.current.getMonth() + 1, 1);
    await loadMonth();
});

document.getElementById('cal-today').addEventListener('click', async () => {
    calendarState.current = new Date();
    await loadMonth();
});

document.getElementById('filter-kind').addEventListener('change', (e) => {
    calendarState.filterKind = e.target.value;
    renderCalendar();
});

document.getElementById('filter-group').addEventListener('change', (e) => {
    calendarState.filterGroup = e.target.value;
    renderCalendar();
});

document.getElementById('close-quick-modal').addEventListener('click', closeQuickModal);
document.getElementById('cancel-quick-modal').addEventListener('click', closeQuickModal);
document.getElementById('close-sunday-modal').addEventListener('click', closeSundayModal);

document.getElementById('quick-event-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = new FormData(e.target);
    const payload = Object.fromEntries(form.entries());
    payload.send_email = form.get('send_email') ? 1 : 0;
    payload.send_sms = form.get('send_sms') ? 1 : 0;

    const res = await fetch(BASE_URL + '/api/v1/events', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload),
    });

    const data = await res.json();
    if (!res.ok || !data.success) {
        alert(data.message || 'Failed to create event');
        return;
    }

    closeQuickModal();
    e.target.reset();
    await loadMonth();
});

Promise.all([loadGroups(), loadUsers(), loadMonth()]).catch((error) => {
    console.error('Dashboard bootstrap failed', error);
});

window.addEventListener('resize', () => {
    fitDashboardViewport();
    renderCalendar();
});

document.addEventListener('DOMContentLoaded', () => {
    fitDashboardViewport();
});
</script>
