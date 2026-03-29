<?php
$eventId = (int) ($eventId ?? 0);
$B = $baseUrl ?? '';
?>
<section class="mb-6">
    <a href="<?= $B ?>/events" class="text-sm text-royal-600 hover:text-royal-800">&larr; Back to Events</a>
    <h1 class="mt-2 text-3xl font-heading font-semibold text-royal-900">Event Details</h1>
    <p class="text-mist-600">Complete operational view: overview, budget, tasks, attendance, communication, and report.</p>
</section>

<section class="bg-white rounded-2xl border border-mist-200 shadow-lg p-5">
    <div id="event-header" class="mb-4"></div>

    <div class="flex flex-wrap gap-2 mb-4" id="tab-buttons">
        <button class="event-tab px-4 py-2 rounded-xl bg-royal-600 text-white" data-tab="overview">Overview</button>
        <button class="event-tab px-4 py-2 rounded-xl bg-mist-100 text-mist-700" data-tab="budget">Budget</button>
        <button class="event-tab px-4 py-2 rounded-xl bg-mist-100 text-mist-700" data-tab="tasks">Tasks</button>
        <button class="event-tab px-4 py-2 rounded-xl bg-mist-100 text-mist-700" data-tab="attendance">Attendance</button>
        <button class="event-tab px-4 py-2 rounded-xl bg-mist-100 text-mist-700" data-tab="communication">Communication</button>
        <button class="event-tab px-4 py-2 rounded-xl bg-mist-100 text-mist-700" data-tab="report">Report</button>
    </div>

    <div id="tab-content"></div>
</section>

<script>
const EVENT_ID = <?= $eventId ?>;
const TAB_WHITELIST = ['overview', 'budget', 'tasks', 'attendance', 'communication', 'report'];
const requestedTab = new URLSearchParams(window.location.search).get('tab');
let detailsData = null;
let currentTab = TAB_WHITELIST.includes(requestedTab) ? requestedTab : 'overview';

function money(v) { return Number(v || 0).toLocaleString(); }

function statusBadge(status) {
    const map = {
        draft: 'bg-mist-100 text-mist-700',
        planned: 'bg-dawn-100 text-dawn-700',
        ongoing: 'bg-glory-100 text-glory-700',
        completed: 'bg-emerald-100 text-emerald-700',
        cancelled: 'bg-red-100 text-red-700',
    };
    return map[status] || 'bg-mist-100 text-mist-700';
}

function renderHeader() {
    const ov = detailsData.overview;
    const dt = new Date(ov.start_datetime).toLocaleString([], { dateStyle: 'full', timeStyle: 'short' });
    const appt = isAppt(ov.notes);
    const apptWith = extractApptWith(ov.notes);
    const subtitleExtra = (appt && apptWith) ? ` &mdash; with <strong>${apptWith}</strong>` : '';
    const apptBadge = appt ? `<span class="ml-2 inline-flex items-center gap-1 bg-rose-100 text-rose-700 rounded-full px-2.5 py-0.5 text-xs font-semibold align-middle">&#128197; Appointment</span>` : '';
    document.getElementById('event-header').innerHTML = `
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <p class="text-xs uppercase tracking-widest text-mist-500">${ov.event_code}</p>
                <h2 class="text-2xl font-heading font-semibold text-royal-800">${ov.title}${apptBadge}</h2>
                <p class="text-sm text-mist-600 mt-1">${dt} | ${ov.venue || 'Venue pending'}${subtitleExtra}</p>
            </div>
            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold ${statusBadge(ov.status)}">${ov.status}</span>
        </div>
    `;
}

function isAppt(notes) {
    return notes && String(notes).toLowerCase().includes('[event_subtype:appointment]');
}

function extractApptWith(notes) {
    if (!notes) return '';
    const m = String(notes).match(/\[appointment_with:([^\]]+)\]/i);
    return m ? m[1].trim() : '';
}

function categoryLabel(category) {
    const map = { conference: 'Worship Service', seminar: 'Seminar', outreach: 'Outreach', fundraiser: 'Fundraiser', youth: 'Youth', choir: 'Choir', other: 'Meeting' };
    return map[category] || category || 'General';
}

function renderOverview() {
    const ov = detailsData.overview;
    const appt = isAppt(ov.notes);
    const apptWith = extractApptWith(ov.notes);

    const typeHtml = appt
        ? `<span class="inline-flex items-center gap-1.5 bg-rose-100 text-rose-700 rounded-full px-2.5 py-0.5 text-xs font-semibold">&#128197; Appointment</span>${apptWith ? `<span class="text-mist-500 ml-2">with <strong class="text-mist-800">${apptWith}</strong></span>` : ''}`
        : categoryLabel(ov.category);

    return `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="rounded-xl border border-mist-200 p-4">
                <p class="text-xs uppercase text-mist-500 tracking-wider">Description</p>
                <p class="text-sm text-mist-700 mt-2">${ov.description || 'No description provided.'}</p>
            </div>
            <div class="rounded-xl border border-mist-200 p-4">
                <p class="text-xs uppercase text-mist-500 tracking-wider">Event Basics</p>
                <div class="mt-2 space-y-1.5 text-sm text-mist-700">
                    <p class="flex items-center gap-2"><strong>Type:</strong> ${typeHtml}</p>
                    <p><strong>Organizer:</strong> ${ov.organizer_name || 'N/A'}</p>
                    <p><strong>Group:</strong> ${ov.target_group || 'N/A'}</p>
                    ${!appt ? `<p><strong>Expected Attendance:</strong> ${ov.expected_attendance || 0}</p>` : ''}
                </div>
            </div>
        </div>
    `;
}

function renderBudget() {
    const budget = detailsData.budget;
    const rows = budget.items.map((item) => `
        <tr class="border-t border-mist-100">
            <td class="px-3 py-2 text-sm">${item.item_name}</td>
            <td class="px-3 py-2 text-sm">${item.item_type}</td>
            <td class="px-3 py-2 text-sm">TZS ${money(item.planned_amount)}</td>
            <td class="px-3 py-2 text-sm">TZS ${money(item.actual_amount)}</td>
        </tr>
    `).join('') || '<tr><td colspan="4" class="px-3 py-3 text-sm text-mist-500">No budget items available.</td></tr>';

    return `
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
            <div class="rounded-xl bg-mist-50 border border-mist-200 p-3"><p class="text-xs text-mist-500">Planned Budget</p><p class="text-xl font-bold text-royal-800">TZS ${money(budget.planned_budget)}</p></div>
            <div class="rounded-xl bg-mist-50 border border-mist-200 p-3"><p class="text-xs text-mist-500">Actual Expenses</p><p class="text-xl font-bold text-royal-800">TZS ${money(budget.actual_expenses)}</p></div>
            <div class="rounded-xl bg-mist-50 border border-mist-200 p-3"><p class="text-xs text-mist-500">Remaining</p><p class="text-xl font-bold text-royal-800">TZS ${money(budget.remaining_balance)}</p></div>
        </div>
        <div class="overflow-x-auto border border-mist-200 rounded-xl">
            <table class="w-full">
                <thead class="bg-mist-50"><tr><th class="px-3 py-2 text-left text-xs text-mist-500 uppercase">Item</th><th class="px-3 py-2 text-left text-xs text-mist-500 uppercase">Type</th><th class="px-3 py-2 text-left text-xs text-mist-500 uppercase">Planned</th><th class="px-3 py-2 text-left text-xs text-mist-500 uppercase">Actual</th></tr></thead>
                <tbody>${rows}</tbody>
            </table>
        </div>
    `;
}

function renderTasks() {
    const rows = detailsData.tasks.map((task) => `
        <div class="rounded-xl border border-mist-200 p-3">
            <div class="flex items-center justify-between gap-2">
                <p class="font-semibold text-royal-800">${task.title}</p>
                <span class="text-xs px-2 py-1 rounded-full bg-mist-100 text-mist-700">${task.task_status}</span>
            </div>
            <p class="text-sm text-mist-600 mt-1">${task.details || 'No details'}</p>
            <p class="text-xs text-mist-500 mt-2">Assigned: ${task.assigned_to} | Priority: ${task.priority}</p>
        </div>
    `).join('') || '<p class="text-sm text-mist-500">No tasks linked to this event.</p>';

    return `<div class="space-y-3">${rows}</div>`;
}

function renderAttendance() {
    const attendance = detailsData.attendance;
    const members = attendance.members.map((m) => `
        <tr class="border-t border-mist-100">
            <td class="px-3 py-2 text-sm">${m.member_name}</td>
            <td class="px-3 py-2 text-sm">${m.phone || '-'}</td>
            <td class="px-3 py-2 text-sm">${m.status}</td>
        </tr>
    `).join('') || '<tr><td colspan="3" class="px-3 py-3 text-sm text-mist-500">No attendance records yet.</td></tr>';

    return `
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
            <div class="rounded-xl bg-mist-50 border border-mist-200 p-3"><p class="text-xs text-mist-500">Registered</p><p class="text-2xl font-bold text-royal-800">${attendance.registered_count}</p></div>
            <div class="rounded-xl bg-mist-50 border border-mist-200 p-3"><p class="text-xs text-mist-500">Present</p><p class="text-2xl font-bold text-royal-800">${attendance.present_count}</p></div>
            <div class="rounded-xl bg-mist-50 border border-mist-200 p-3"><p class="text-xs text-mist-500">Absent</p><p class="text-2xl font-bold text-royal-800">${attendance.absent_count}</p></div>
        </div>
        <div class="overflow-x-auto border border-mist-200 rounded-xl">
            <table class="w-full"><thead class="bg-mist-50"><tr><th class="px-3 py-2 text-left text-xs text-mist-500 uppercase">Member</th><th class="px-3 py-2 text-left text-xs text-mist-500 uppercase">Phone</th><th class="px-3 py-2 text-left text-xs text-mist-500 uppercase">Status</th></tr></thead><tbody>${members}</tbody></table>
        </div>
    `;
}

function renderCommunication() {
    const rows = detailsData.communication.sms_logs.map((log) => `
        <tr class="border-t border-mist-100">
            <td class="px-3 py-2 text-xs">${log.sent_at || '-'}</td>
            <td class="px-3 py-2 text-xs">${log.phone || '-'}</td>
            <td class="px-3 py-2 text-xs">${log.delivery_status}</td>
            <td class="px-3 py-2 text-xs">${log.message_text}</td>
        </tr>
    `).join('') || '<tr><td colspan="4" class="px-3 py-3 text-sm text-mist-500">No communication logs yet.</td></tr>';

    return `
        <form id="communication-form" class="space-y-3 mb-4">
            <textarea name="message" required rows="3" placeholder="Write reminder message..." class="w-full rounded-xl border border-mist-200 px-3 py-2 text-sm"></textarea>
            <div class="flex items-center gap-4 text-sm">
                <label class="inline-flex items-center gap-2"><input type="checkbox" name="send_email" value="1">Send email</label>
                <label class="inline-flex items-center gap-2"><input type="checkbox" name="send_sms" value="1" checked>Send SMS</label>
            </div>
            <button type="submit" class="px-4 py-2 rounded-xl bg-royal-600 text-white hover:bg-royal-700">Send Reminder</button>
        </form>
        <div class="overflow-x-auto border border-mist-200 rounded-xl">
            <table class="w-full"><thead class="bg-mist-50"><tr><th class="px-3 py-2 text-left text-xs text-mist-500 uppercase">Sent At</th><th class="px-3 py-2 text-left text-xs text-mist-500 uppercase">Phone</th><th class="px-3 py-2 text-left text-xs text-mist-500 uppercase">Status</th><th class="px-3 py-2 text-left text-xs text-mist-500 uppercase">Message</th></tr></thead><tbody>${rows}</tbody></table>
        </div>
    `;
}

function renderReport() {
    const report = detailsData.report;
    return `
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
            <div class="rounded-xl border border-mist-200 p-3"><p class="text-xs text-mist-500">Total Income</p><p class="text-xl font-bold text-royal-800">TZS ${money(report.income_total)}</p></div>
            <div class="rounded-xl border border-mist-200 p-3"><p class="text-xs text-mist-500">Total Expenses</p><p class="text-xl font-bold text-royal-800">TZS ${money(report.expense_total)}</p></div>
            <div class="rounded-xl border border-mist-200 p-3"><p class="text-xs text-mist-500">Final Balance</p><p class="text-xl font-bold text-royal-800">TZS ${money(report.net_total)}</p></div>
        </div>
        <div class="rounded-xl border border-mist-200 p-4">
            <p class="text-xs text-mist-500 uppercase tracking-wider">Final Summary</p>
            <p class="text-sm text-mist-700 mt-2">${report.final_summary}</p>
        </div>
    `;
}

function syncTabButtons() {
    document.querySelectorAll('.event-tab').forEach((btn) => {
        const active = btn.dataset.tab === currentTab;
        btn.classList.toggle('bg-royal-600', active);
        btn.classList.toggle('text-white', active);
        btn.classList.toggle('bg-mist-100', !active);
        btn.classList.toggle('text-mist-700', !active);
    });
}

function renderTab() {
    const content = document.getElementById('tab-content');
    if (!detailsData) {
        content.innerHTML = '<p class="text-sm text-mist-500">Loading...</p>';
        return;
    }

    if (currentTab === 'overview') content.innerHTML = renderOverview();
    if (currentTab === 'budget') content.innerHTML = renderBudget();
    if (currentTab === 'tasks') content.innerHTML = renderTasks();
    if (currentTab === 'attendance') content.innerHTML = renderAttendance();
    if (currentTab === 'communication') content.innerHTML = renderCommunication();
    if (currentTab === 'report') content.innerHTML = renderReport();

    const form = document.getElementById('communication-form');
    if (form) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const fd = new FormData(form);
            const payload = Object.fromEntries(fd.entries());
            payload.send_email = fd.get('send_email') ? 1 : 0;
            payload.send_sms = fd.get('send_sms') ? 1 : 0;

            const res = await fetch(BASE_URL + `/api/v1/events/${EVENT_ID}/communicate`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload),
            });
            const data = await res.json();
            if (!res.ok || !data.success) {
                alert(data.message || 'Failed to send communication');
                return;
            }
            alert(`Queued. SMS: ${data.data.sms_queued}, Email prepared: ${data.data.email_prepared}`);
            await loadDetails();
            currentTab = 'communication';
            syncTabButtons();
            renderTab();
        });
    }
}

async function loadDetails() {
    const res = await fetch(BASE_URL + `/api/v1/events/${EVENT_ID}/details`);
    const payload = await res.json();
    if (!res.ok || !payload.success) {
        throw new Error(payload.message || 'Unable to load event details');
    }

    detailsData = payload.data;
    renderHeader();
    syncTabButtons();
    renderTab();
}

document.querySelectorAll('.event-tab').forEach((btn) => {
    btn.addEventListener('click', () => {
        currentTab = btn.dataset.tab;
        syncTabButtons();
        renderTab();
    });
});

loadDetails().catch((error) => {
    console.error(error);
    document.getElementById('tab-content').innerHTML = '<p class="text-sm text-red-600">Unable to load event details.</p>';
});
</script>
