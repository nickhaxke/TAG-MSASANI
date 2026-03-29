<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div>
        <h1 class="text-3xl font-heading font-semibold text-royal-900">Event And Appointment Center</h1>
        <p class="text-mist-600 text-sm">Create appointments and events, track budgets, and hand over finance follow-up smoothly.</p>
    </div>
    <a href="<?= $B ?>/" class="px-4 py-2 rounded-xl bg-royal-600 text-white hover:bg-royal-700 text-sm">Open Calendar Dashboard</a>
</div>

<section class="bg-white rounded-2xl border border-mist-200 shadow-lg p-5 mb-6">
    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
        <h2 class="text-xl font-heading font-semibold text-royal-800">Create Event Or Appointment</h2>
        <p class="text-xs text-mist-500">If budget is entered, a budget item is auto-created and can be reviewed by accountant.</p>
    </div>

    <form id="create-event-form" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
        <input name="title" required placeholder="Event / Appointment title" class="rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
        <select id="event-type" name="event_type" required class="rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
            <option value="">Type</option>
            <option value="service">Service</option>
            <option value="seminar">Seminar</option>
            <option value="meeting">Meeting</option>
            <option value="appointment">Appointment</option>
        </select>
        <input name="date" type="date" required class="rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
        <input name="time" type="time" required class="rounded-xl border border-mist-200 px-3 py-2.5 text-sm">

        <input name="location" placeholder="Location" class="rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
        <input id="event-budget" name="budget" type="number" min="0" step="0.01" placeholder="Budget (TZS)" class="rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
        <div id="attendance-wrap"><input name="expected_attendance" type="number" min="0" placeholder="Expected attendance" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm"></div>
        <div id="appt-with-wrap" class="hidden"><input id="appt-with" name="appointment_with" placeholder="Appointment with (e.g., Pastor Grace, Elder John)" class="w-full rounded-xl border border-rose-300 bg-rose-50 px-3 py-2.5 text-sm placeholder-rose-400 focus:outline-none focus:ring-2 focus:ring-rose-300"></div>
        <input id="duration-hours" name="duration_hours" type="number" min="1" max="24" step="1" value="2" placeholder="Duration (hours)" class="rounded-xl border border-mist-200 px-3 py-2.5 text-sm">

        <select id="organizer-user" name="organizer_user_id" class="rounded-xl border border-mist-200 px-3 py-2.5 text-sm"><option value="">Organizer person</option></select>
        <select id="target-group" name="target_group_id" class="rounded-xl border border-mist-200 px-3 py-2.5 text-sm"><option value="">Organizer group</option></select>
        <label class="inline-flex items-center gap-2 text-sm text-mist-700 rounded-xl border border-mist-200 px-3 py-2.5">
            <input id="notify-accountant" type="checkbox" value="1">Notify accountant for budget follow-up
        </label>
        <div class="inline-flex items-center gap-3 text-sm text-mist-700 rounded-xl border border-mist-200 px-3 py-2.5">
            <label class="inline-flex items-center gap-2"><input type="checkbox" name="send_email" value="1">Email</label>
            <label class="inline-flex items-center gap-2"><input type="checkbox" name="send_sms" value="1">SMS</label>
        </div>

        <div id="appt-banner" class="hidden lg:col-span-4 flex items-center gap-3 bg-rose-50 border border-rose-200 rounded-xl px-4 py-2.5">
            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-white bg-rose-500 rounded-full px-3 py-1">&#128197; Appointment</span>
            <span class="text-sm text-rose-800">This is a personal appointment. The <strong>"Appointment With"</strong> name will appear on the calendar.</span>
        </div>
        <textarea id="event-description" name="description" rows="2" placeholder="Description / agenda" class="md:col-span-2 lg:col-span-3 rounded-xl border border-mist-200 px-3 py-2.5 text-sm"></textarea>
        <div class="flex items-end justify-end">
            <button type="submit" class="w-full md:w-auto px-5 py-2.5 rounded-xl bg-royal-600 text-white hover:bg-royal-700 text-sm font-semibold">Create</button>
        </div>
    </form>

    <div id="create-feedback" class="hidden mt-3 rounded-xl px-3 py-2 text-sm"></div>
</section>

<section class="bg-white rounded-2xl border border-mist-200 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-mist-100 flex flex-wrap items-center gap-3 justify-between">
        <h2 class="font-semibold text-royal-800">All Events</h2>
        <div class="flex flex-wrap gap-2">
            <input id="search-keyword" type="text" placeholder="Search code or title" class="rounded-xl border border-mist-200 px-3 py-2 text-sm">
            <input id="filter-month" type="month" class="rounded-xl border border-mist-200 px-3 py-2 text-sm">
            <select id="filter-type" class="rounded-xl border border-mist-200 px-3 py-2 text-sm">
                <option value="">All categories</option>
                <option value="conference">Worship service</option>
                <option value="seminar">Seminar</option>
                <option value="other">Meeting / Appointment</option>
                <option value="youth">Youth</option>
            </select>
            <select id="filter-group-list" class="rounded-xl border border-mist-200 px-3 py-2 text-sm">
                <option value="">All groups</option>
            </select>
            <button id="apply-filters" class="px-3 py-2 rounded-xl bg-mist-100 text-mist-700 hover:bg-mist-200 text-sm">Apply</button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-mist-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-mist-600">Code</th>
                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-mist-600">Event</th>
                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-mist-600">Date</th>
                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-mist-600">Budget</th>
                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-mist-600">Status</th>
                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-mist-600">Budget Workflow</th>
                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-mist-600">Open</th>
                </tr>
            </thead>
            <tbody id="events-body" class="divide-y divide-mist-100"></tbody>
        </table>
    </div>
    <div id="events-empty" class="hidden px-5 py-10 text-center text-mist-500">No events for selected filters.</div>
</section>

<script>
function formatMoney(value) {
    return Number(value || 0).toLocaleString();
}

function statusClass(status) {
    const map = {
        draft: 'bg-mist-100 text-mist-700',
        planned: 'bg-dawn-100 text-dawn-700',
        ongoing: 'bg-glory-100 text-glory-700',
        completed: 'bg-emerald-100 text-emerald-700',
        cancelled: 'bg-red-100 text-red-700',
    };
    return map[status] || 'bg-mist-100 text-mist-700';
}

function categoryLabel(category) {
    const map = {
        conference: 'Service',
        seminar: 'Seminar',
        other: 'Meeting / Appointment',
        youth: 'Youth',
    };
    return map[category] || category || 'General';
}

function isAppointmentRow(notes) {
    return notes && String(notes).toLowerCase().includes('[event_subtype:appointment]');
}

function extractApptWith(notes) {
    if (!notes) return '';
    const m = String(notes).match(/\[appointment_with:([^\]]+)\]/i);
    return m ? m[1].trim() : '';
}

function showCreateFeedback(message, isError = false) {
    const el = document.getElementById('create-feedback');
    el.classList.remove('hidden');
    el.textContent = message;
    el.className = `mt-3 rounded-xl px-3 py-2 text-sm ${isError ? 'bg-red-50 text-red-700 border border-red-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200'}`;
}

async function loadGroups() {
    const res = await fetch(BASE_URL + '/api/v1/meta/groups');
    const payload = await res.json();
    const groups = payload.data || [];
    const dropdowns = [document.getElementById('target-group'), document.getElementById('filter-group-list')];
    dropdowns.forEach((select, i) => {
        const label = i === 0 ? 'Organizer group' : 'All groups';
        select.innerHTML = `<option value="">${label}</option>` + groups.map((g) => `<option value="${g.id}">${g.name}</option>`).join('');
    });
}

async function loadUsers() {
    const res = await fetch(BASE_URL + '/api/v1/meta/users');
    const payload = await res.json();
    const users = payload.data || [];
    const select = document.getElementById('organizer-user');
    select.innerHTML = '<option value="">Organizer person</option>' + users.map((u) => `<option value="${u.id}">${u.full_name}</option>`).join('');
}

function buildBudgetActions(row) {
    if (Number(row.budget_total || 0) <= 0) {
        return '<span class="text-xs text-mist-500">No budget linked</span>';
    }

    return `
        <div class="flex flex-wrap items-center gap-2">
            <a class="text-xs font-semibold text-royal-700 hover:text-royal-900" href="${BASE_URL}/events/${row.id}?tab=budget">Open Budget</a>
            <span class="text-mist-300">|</span>
            <a class="text-xs font-semibold text-emerald-700 hover:text-emerald-900" href="${BASE_URL}/finance?event_id=${row.id}">Send To Finance</a>
        </div>
    `;
}

async function loadEvents() {
    const month = document.getElementById('filter-month').value;
    const type = document.getElementById('filter-type').value;
    const group = document.getElementById('filter-group-list').value;
    const keyword = document.getElementById('search-keyword').value.trim().toLowerCase();

    const params = new URLSearchParams();
    if (month) params.set('month', month);
    if (type) params.set('type', type);
    if (group) params.set('group', group);

    const res = await fetch(BASE_URL + '/api/v1/events?' + params.toString());
    const payload = await res.json();
    let rows = payload.data || [];

    if (keyword !== '') {
        rows = rows.filter((row) => {
            const haystack = `${row.event_code || ''} ${row.title || ''}`.toLowerCase();
            return haystack.includes(keyword);
        });
    }

    const body = document.getElementById('events-body');
    const empty = document.getElementById('events-empty');

    if (rows.length === 0) {
        body.innerHTML = '';
        empty.classList.remove('hidden');
        return;
    }

    empty.classList.add('hidden');
    body.innerHTML = rows.map((row) => {
        const dt = new Date(row.start_datetime).toLocaleString([], { dateStyle: 'medium', timeStyle: 'short' });
        const budget = Number(row.budget_total || 0);
        return `
            <tr class="hover:bg-mist-50/70">
                <td class="px-4 py-3 text-xs text-mist-600 font-mono">${row.event_code}</td>
                <td class="px-4 py-3">
                    <p class="font-semibold text-royal-800">${row.title}</p>
                    ${isAppointmentRow(row.notes)
                        ? `<p class="text-xs mt-0.5"><span class="inline-flex items-center gap-1 bg-rose-100 text-rose-700 rounded-full px-2 py-0.5 text-[10px] font-semibold">&#128197; Appointment</span>${extractApptWith(row.notes) ? `<span class="text-mist-500 ml-2">with ${extractApptWith(row.notes)}</span>` : ''}</p>`
                        : `<p class="text-xs text-mist-500">${categoryLabel(row.category)}${row.target_group ? ` &bull; ${row.target_group}` : ''}</p>`}
                </td>
                <td class="px-4 py-3 text-mist-700">${dt}<div class="text-xs text-mist-500">${row.venue || '-'}</div></td>
                <td class="px-4 py-3 text-mist-700">TZS ${formatMoney(budget)}${budget > 0 ? '<div class="text-xs text-emerald-600 font-semibold">Linked</div>' : ''}</td>
                <td class="px-4 py-3"><span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold ${statusClass(row.status)}">${row.status}</span></td>
                <td class="px-4 py-3">${buildBudgetActions(row)}</td>
                <td class="px-4 py-3"><a class="text-royal-600 hover:text-royal-800 font-semibold text-xs" href="${BASE_URL}/events/${row.id}">Open</a></td>
            </tr>
        `;
    }).join('');
}

document.getElementById('apply-filters').addEventListener('click', loadEvents);
document.getElementById('search-keyword').addEventListener('input', loadEvents);

document.getElementById('event-type').addEventListener('change', (e) => {
    const val = e.target.value;
    const isAppt = val === 'appointment';
    const duration = document.getElementById('duration-hours');

    if (isAppt) {
        duration.value = '1';
    } else if (Number(duration.value) < 2) {
        duration.value = '2';
    }

    document.getElementById('attendance-wrap').classList.toggle('hidden', isAppt);
    document.getElementById('appt-with-wrap').classList.toggle('hidden', !isAppt);
    document.getElementById('appt-banner').classList.toggle('hidden', !isAppt);

    const titleInput = document.querySelector('[name="title"]');
    titleInput.placeholder = isAppt ? 'Appointment title / subject' : 'Event / Appointment title';
});

document.getElementById('create-event-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const fd = new FormData(e.target);
    const payload = Object.fromEntries(fd.entries());
    const budget = Number(payload.budget || 0);
    const notifyAccountant = document.getElementById('notify-accountant').checked;

    payload.send_email = fd.get('send_email') ? 1 : 0;
    payload.send_sms = fd.get('send_sms') ? 1 : 0;

    const apptWith = (document.getElementById('appt-with')?.value || '').trim();
    if (payload.event_type === 'appointment' && apptWith) {
        payload.appointment_with = apptWith;
    }

    if (notifyAccountant && budget > 0) {
        const note = '\n\n[Finance workflow] Budget entered and marked for accountant follow-up.';
        payload.description = (payload.description || '') + note;
        payload.send_email = 1;
    }

    const res = await fetch(BASE_URL + '/api/v1/events', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload),
    });

    const data = await res.json();
    if (!res.ok || !data.success) {
        showCreateFeedback(data.message || 'Failed to create event', true);
        return;
    }

    e.target.reset();
    showCreateFeedback('Event created successfully. If budget was entered, it is now available under Budget tab for accountant follow-up.');
    await loadEvents();
});

const now = new Date();
document.getElementById('filter-month').value = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}`;

Promise.all([loadGroups(), loadUsers(), loadEvents()]).catch((error) => {
    console.error('Events page bootstrap failed', error);
});
</script>
