<?php $B = $baseUrl ?? ''; ?>

<!--  Page Header  -->
<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div>
        <h1 class="text-3xl font-heading font-semibold text-royal-900">Church Members</h1>
        <p class="text-mist-600 text-sm mt-0.5">Register, manage and import congregation members.</p>
    </div>
    <div class="flex flex-wrap gap-2">
        <button id="btn-export-csv" class="px-4 py-2 rounded-xl bg-mist-100 text-mist-700 hover:bg-mist-200 text-sm font-medium">&#11015; Export CSV</button>
        <button id="btn-open-import" class="px-4 py-2 rounded-xl bg-emerald-600 text-white hover:bg-emerald-700 text-sm font-semibold">&#8679; Import Excel / CSV</button>
        <button id="btn-open-add" class="px-4 py-2 rounded-xl bg-royal-600 text-white hover:bg-royal-700 text-sm font-semibold">+ Add Member</button>
    </div>
</div>

<!--  Stats Bar  -->
<div id="stats-bar" class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
    <div class="bg-white rounded-2xl border border-mist-200 p-4"><p class="text-xs text-mist-500 uppercase tracking-wide">Total</p><p id="stat-total" class="text-2xl font-bold text-royal-800 mt-1">--</p></div>
    <div class="bg-white rounded-2xl border border-mist-200 p-4"><p class="text-xs text-emerald-600 uppercase tracking-wide">Active</p><p id="stat-active" class="text-2xl font-bold text-emerald-700 mt-1">--</p></div>
    <div class="bg-white rounded-2xl border border-mist-200 p-4"><p class="text-xs text-mist-500 uppercase tracking-wide">Inactive</p><p id="stat-inactive" class="text-2xl font-bold text-mist-600 mt-1">--</p></div>
    <div class="bg-white rounded-2xl border border-mist-200 p-4"><p class="text-xs text-dawn-600 uppercase tracking-wide">Transferred</p><p id="stat-transferred" class="text-2xl font-bold text-dawn-700 mt-1">--</p></div>
</div>

<!--  Filters  -->
<div class="bg-white rounded-2xl border border-mist-200 shadow-sm px-4 py-3 mb-4">
    <div class="flex flex-wrap gap-2">
        <input id="filter-search" type="text" placeholder="Search name, phone, code, email" class="flex-1 min-w-48 rounded-xl border border-mist-200 px-3 py-2 text-sm">
        <select id="filter-status" class="rounded-xl border border-mist-200 px-3 py-2 text-sm">
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="transferred">Transferred</option>
            <option value="deceased">Deceased</option>
        </select>
        <select id="filter-gender" class="rounded-xl border border-mist-200 px-3 py-2 text-sm">
            <option value="">All Gender</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
        </select>
        <select id="filter-region" class="rounded-xl border border-mist-200 px-3 py-2 text-sm">
            <option value="">All Regions</option>
        </select>
        <button id="btn-clear-filters" class="px-3 py-2 rounded-xl bg-mist-100 text-mist-600 hover:bg-mist-200 text-sm">Clear</button>
    </div>
</div>

<!--  Members Table  -->
<div class="bg-white rounded-2xl border border-mist-200 shadow-sm overflow-hidden">
    <div class="px-5 py-3.5 border-b border-mist-100 flex flex-wrap items-center justify-between gap-2">
        <h2 class="font-semibold text-royal-800">Members List</h2>
        <span id="member-count" class="text-xs text-mist-500"></span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-mist-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-mist-500">Code</th>
                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-mist-500">Full Name</th>
                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-mist-500">Phone</th>
                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-mist-500">Email</th>
                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-mist-500">Gender</th>
                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-mist-500">Region</th>
                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-mist-500">Status</th>
                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-mist-500">Joined</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody id="members-tbody" class="divide-y divide-mist-100"></tbody>
        </table>
    </div>
    <div id="members-empty" class="hidden px-5 py-14 text-center text-mist-400">
        <p class="text-4xl mb-2">&#128100;</p>
        <p class="font-semibold text-mist-600">No members found</p>
        <p class="text-sm mt-1">Add members manually or import from Excel/CSV.</p>
    </div>
</div>

<!-- 
     ADD / EDIT MEMBER MODAL
 -->
<div id="member-modal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-start justify-center min-h-screen pt-10 pb-10 px-4">
        <div class="fixed inset-0 bg-black/40" id="member-modal-bg"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl z-10">
            <div class="flex items-center justify-between px-6 py-4 border-b border-mist-100">
                <h3 id="modal-title" class="text-lg font-heading font-semibold text-royal-900">Add New Member</h3>
                <button id="btn-close-member-modal" class="p-1.5 rounded-lg hover:bg-mist-100 text-mist-500">&#10005;</button>
            </div>
            <form id="member-form" class="px-6 py-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="hidden" id="edit-member-id" value="">
                <!-- Row 1 -->
                <div>
                    <label class="block text-xs font-semibold text-mist-600 mb-1">First Name <span class="text-red-500">*</span></label>
                    <input name="first_name" required placeholder="First name" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-mist-600 mb-1">Last Name <span class="text-red-500">*</span></label>
                    <input name="last_name" required placeholder="Last name / Surname" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                </div>
                <!-- Row 2 -->
                <div>
                    <label class="block text-xs font-semibold text-mist-600 mb-1">Gender <span class="text-red-500">*</span></label>
                    <select name="gender" required class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                        <option value="">Select</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-mist-600 mb-1">Phone <span class="text-red-500">*</span></label>
                    <input name="phone" required placeholder="+255 7XX XXX XXX" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                </div>
                <!-- Row 3 -->
                <div>
                    <label class="block text-xs font-semibold text-mist-600 mb-1">Email</label>
                    <input type="email" name="email" placeholder="email@example.com" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-mist-600 mb-1">Member Code <span class="text-mist-400 font-normal">(auto if blank)</span></label>
                    <input name="member_code" placeholder="MBR-2026-0001" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm font-mono">
                </div>
                <!-- Row 4 -->
                <div>
                    <label class="block text-xs font-semibold text-mist-600 mb-1">Date of Birth</label>
                    <input type="date" name="date_of_birth" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-mist-600 mb-1">Join Date</label>
                    <input type="date" name="join_date" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                </div>
                <!-- Row 5 -->
                <div>
                    <label class="block text-xs font-semibold text-mist-600 mb-1">Marital Status</label>
                    <select name="marital_status" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                        <option value="">Select</option>
                        <option value="single">Single</option>
                        <option value="married">Married</option>
                        <option value="widowed">Widowed</option>
                        <option value="divorced">Divorced</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-mist-600 mb-1">Baptism Date</label>
                    <input type="date" name="baptism_date" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                </div>
                <!-- Row 6 -->
                <div>
                    <label class="block text-xs font-semibold text-mist-600 mb-1">Ward / Mtaa</label>
                    <input name="ward" placeholder="Mtaa / Ward" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-mist-600 mb-1">District / Wilaya</label>
                    <input name="district" placeholder="District" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                </div>
                <!-- Row 7 -->
                <div>
                    <label class="block text-xs font-semibold text-mist-600 mb-1">Region / Mkoa</label>
                    <input name="region" placeholder="Region" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-mist-600 mb-1">Status</label>
                    <select name="member_status" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="transferred">Transferred</option>
                        <option value="deceased">Deceased</option>
                    </select>
                </div>
                <!-- Notes full width -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-mist-600 mb-1">Notes / Maelezo</label>
                    <textarea name="notes" rows="2" placeholder="Optional notes" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm"></textarea>
                </div>
                <!-- Buttons -->
                <div class="md:col-span-2 flex justify-end gap-3 pt-2 border-t border-mist-100">
                    <button type="button" id="btn-cancel-member" class="px-4 py-2.5 rounded-xl bg-mist-100 text-mist-700 hover:bg-mist-200 text-sm font-medium">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-royal-600 text-white hover:bg-royal-700 text-sm font-semibold">Save Member</button>
                </div>
            </form>
            <div id="member-form-feedback" class="hidden mx-6 mb-4 rounded-xl px-3 py-2 text-sm"></div>
        </div>
    </div>
</div>

<!-- 
     IMPORT MODAL
 -->
<div id="import-modal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-start justify-center min-h-screen pt-10 pb-10 px-4">
        <div class="fixed inset-0 bg-black/40" id="import-modal-bg"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-xl z-10">
            <div class="flex items-center justify-between px-6 py-4 border-b border-mist-100">
                <h3 class="text-lg font-heading font-semibold text-royal-900">&#8679; Import Members</h3>
                <button id="btn-close-import" class="p-1.5 rounded-lg hover:bg-mist-100 text-mist-500">&#10005;</button>
            </div>
            <div class="px-6 py-5 space-y-5">

                <!-- Step 1: Template -->
                <div class="bg-royal-50 border border-royal-200 rounded-xl p-4">
                    <p class="text-sm font-semibold text-royal-800 mb-1">Step 1 &mdash; Download Template</p>
                    <p class="text-xs text-royal-700 mb-3">Download the CSV template. Fill it in Excel or Google Sheets, then save as <strong>.csv</strong> or <strong>.xlsx</strong>.</p>
                    <button id="btn-download-template" class="px-4 py-2 rounded-xl bg-royal-600 text-white text-sm hover:bg-royal-700 font-semibold">&#11015; Download CSV Template</button>
                </div>

                <!-- Step 2: Upload -->
                <div>
                    <p class="text-sm font-semibold text-mist-700 mb-2">Step 2 &mdash; Upload File</p>
                    <label id="drop-zone" class="block cursor-pointer border-2 border-dashed border-mist-300 rounded-xl px-4 py-8 text-center hover:border-royal-400 hover:bg-royal-50/30 transition-colors">
                        <input type="file" id="import-file-input" accept=".csv,.xlsx" class="hidden">
                        <p class="text-3xl mb-2">&#128196;</p>
                        <p class="text-sm font-semibold text-mist-700">Click to choose file or drag &amp; drop here</p>
                        <p class="text-xs text-mist-500 mt-1">Supported: <strong>.csv</strong> and <strong>.xlsx</strong></p>
                        <p id="selected-filename" class="text-sm font-semibold text-royal-700 mt-2 hidden"></p>
                    </label>
                </div>

                <!-- Supported columns reference -->
                <details class="text-xs text-mist-600">
                    <summary class="cursor-pointer font-semibold text-mist-700 hover:text-royal-700">Supported column names (click to expand)</summary>
                    <div class="mt-2 grid grid-cols-2 gap-1 pl-2">
                        <span><strong>first_name</strong> / First Name / Jina</span>
                        <span><strong>last_name</strong> / Surname / Familia</span>
                        <span><strong>gender</strong> / Jinsia</span>
                        <span><strong>phone</strong> / Simu / Mobile</span>
                        <span><strong>email</strong> / Barua Pepe</span>
                        <span><strong>date_of_birth</strong> / DOB</span>
                        <span><strong>join_date</strong> / Joined</span>
                        <span><strong>member_status</strong> / Status / Hali</span>
                        <span><strong>member_code</strong> / Code / Nambari</span>
                        <span><strong>ward</strong> / Mtaa</span>
                        <span><strong>district</strong> / Wilaya</span>
                        <span><strong>region</strong> / Mkoa</span>
                        <span><strong>marital_status</strong> / Hali ya Ndoa</span>
                        <span><strong>baptism_date</strong> / Tarehe ya Ubatizo</span>
                        <span><strong>notes</strong> / Maelezo</span>
                    </div>
                </details>

                <!-- Import Button -->
                <div class="flex justify-end gap-3 pt-2 border-t border-mist-100">
                    <button id="btn-cancel-import" class="px-4 py-2.5 rounded-xl bg-mist-100 text-mist-700 hover:bg-mist-200 text-sm font-medium">Cancel</button>
                    <button id="btn-do-import" disabled class="px-6 py-2.5 rounded-xl bg-emerald-600 text-white hover:bg-emerald-700 text-sm font-semibold disabled:opacity-40 disabled:cursor-not-allowed">Import Members</button>
                </div>

                <!-- Result -->
                <div id="import-result" class="hidden rounded-xl px-4 py-3 text-sm"></div>
                <div id="import-errors" class="hidden bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-xs text-red-700 space-y-1 max-h-40 overflow-y-auto"></div>
            </div>
        </div>
    </div>
</div>

<script>
const MB = '<?= $B ?>';
let allMembers = [];

/*  Stats  */
async function loadStats() {
    try {
        const res  = await fetch(MB + '/api/v1/members/stats');
        const data = await res.json();
        const s = data.data || {};
        document.getElementById('stat-total').textContent       = s.total       ?? '0';
        document.getElementById('stat-active').textContent      = s.active      ?? '0';
        document.getElementById('stat-inactive').textContent    = s.inactive    ?? '0';
        document.getElementById('stat-transferred').textContent = s.transferred ?? '0';
    } catch(_) {}
}

/*  Load Members  */
async function loadMembers() {
    const search = document.getElementById('filter-search').value.trim();
    const status = document.getElementById('filter-status').value;
    const gender = document.getElementById('filter-gender').value;
    const params = new URLSearchParams();
    if (search) params.set('search', search);
    if (status) params.set('status', status);
    if (gender) params.set('gender', gender);

    try {
        const res  = await fetch(MB + '/api/v1/members?' + params.toString());
        const data = await res.json();
        allMembers = data.data || [];
        renderMembers(allMembers);
        rebuildRegionFilter();
    } catch(e) {
        console.error('Failed to load members', e);
    }
}

function rebuildRegionFilter() {
    const sel = document.getElementById('filter-region');
    const current = sel.value;
    const regions = [...new Set(allMembers.map(m => m.region).filter(Boolean))].sort();
    sel.innerHTML = '<option value="">All Regions</option>' + regions.map(r => `<option value="${r}"${r===current?' selected':''}>${r}</option>`).join('');
}

function applyClientFilter() {
    const region = document.getElementById('filter-region').value;
    if (!region) { renderMembers(allMembers); return; }
    renderMembers(allMembers.filter(m => m.region === region));
}

/*  Render Table  */
function statusCls(s) {
    const m = { active:'bg-emerald-100 text-emerald-700', inactive:'bg-mist-100 text-mist-600', transferred:'bg-dawn-100 text-dawn-700', deceased:'bg-red-100 text-red-700' };
    return m[s] || 'bg-mist-100 text-mist-600';
}

function renderMembers(list) {
    const tbody = document.getElementById('members-tbody');
    const empty = document.getElementById('members-empty');
    document.getElementById('member-count').textContent = list.length + ' record' + (list.length !== 1 ? 's' : '');
    if (!list.length) { tbody.innerHTML = ''; empty.classList.remove('hidden'); return; }
    empty.classList.add('hidden');
    tbody.innerHTML = list.map(r => `
        <tr class="hover:bg-mist-50/60 cursor-pointer" data-id="${r.id}">
            <td class="px-4 py-3 text-xs font-mono text-mist-600">${r.member_code || '-'}</td>
            <td class="px-4 py-3 font-semibold text-royal-800">${r.first_name} ${r.last_name}</td>
            <td class="px-4 py-3 text-mist-700">${r.phone || '-'}</td>
            <td class="px-4 py-3 text-mist-600 text-xs">${r.email || '-'}</td>
            <td class="px-4 py-3 text-mist-600 capitalize text-xs">${r.gender || '-'}</td>
            <td class="px-4 py-3 text-mist-600 text-xs">${r.region || '-'}</td>
            <td class="px-4 py-3"><span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold ${statusCls(r.member_status)}">${r.member_status}</span></td>
            <td class="px-4 py-3 text-mist-600 text-xs">${r.join_date ? r.join_date.substring(0,10) : '-'}</td>
            <td class="px-4 py-3 text-right">
                <button class="text-xs text-royal-600 hover:text-royal-800 font-semibold edit-btn" data-id="${r.id}">Edit</button>
            </td>
        </tr>
    `).join('');

    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', e => { e.stopPropagation(); openEditModal(Number(btn.dataset.id)); });
    });
}

/*  Filters  */
document.getElementById('filter-search').addEventListener('input',  loadMembers);
document.getElementById('filter-status').addEventListener('change', loadMembers);
document.getElementById('filter-gender').addEventListener('change', loadMembers);
document.getElementById('filter-region').addEventListener('change', applyClientFilter);
document.getElementById('btn-clear-filters').addEventListener('click', () => {
    ['filter-search','filter-status','filter-gender','filter-region'].forEach(id => {
        const el = document.getElementById(id);
        el.value = '';
    });
    loadMembers();
});

/*  Export CSV  */
document.getElementById('btn-export-csv').addEventListener('click', () => {
    if (!allMembers.length) { alert('No members loaded to export.'); return; }
    const cols = ['member_code','first_name','last_name','gender','phone','email','member_status','join_date','ward','district','region','date_of_birth'];
    const header = cols.join(',');
    const rows = allMembers.map(r => cols.map(c => JSON.stringify(r[c] ?? '')).join(','));
    const blob = new Blob([header + '\n' + rows.join('\n')], { type: 'text/csv' });
    const a = Object.assign(document.createElement('a'), { href: URL.createObjectURL(blob), download: 'members_export.csv' });
    a.click();
});

/*  ADD MEMBER MODAL  */
function openAddModal() {
    document.getElementById('edit-member-id').value = '';
    document.getElementById('modal-title').textContent = 'Add New Member';
    document.getElementById('member-form').reset();
    hideFeedback();
    document.getElementById('member-modal').classList.remove('hidden');
}

function openEditModal(id) {
    const m = allMembers.find(x => x.id === id);
    if (!m) return;
    document.getElementById('edit-member-id').value = id;
    document.getElementById('modal-title').textContent = 'Edit Member  ' + m.first_name + ' ' + m.last_name;
    const f = document.getElementById('member-form');
    const fields = ['first_name','last_name','gender','phone','email','member_code','date_of_birth','join_date','marital_status','baptism_date','ward','district','region','member_status','notes'];
    fields.forEach(name => {
        const el = f.querySelector('[name="' + name + '"]');
        if (el) el.value = m[name] || '';
    });
    hideFeedback();
    document.getElementById('member-modal').classList.remove('hidden');
}

function closeMemberModal() { document.getElementById('member-modal').classList.add('hidden'); }

document.getElementById('btn-open-add').addEventListener('click', openAddModal);
document.getElementById('btn-close-member-modal').addEventListener('click', closeMemberModal);
document.getElementById('btn-cancel-member').addEventListener('click', closeMemberModal);
document.getElementById('member-modal-bg').addEventListener('click', closeMemberModal);

function showFeedback(msg, isError) {
    const el = document.getElementById('member-form-feedback');
    el.textContent = msg;
    el.className = 'mx-6 mb-4 rounded-xl px-3 py-2 text-sm ' + (isError ? 'bg-red-50 text-red-700 border border-red-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200');
    el.classList.remove('hidden');
}
function hideFeedback() { document.getElementById('member-form-feedback').classList.add('hidden'); }

document.getElementById('member-form').addEventListener('submit', async e => {
    e.preventDefault();
    const fd      = new FormData(e.target);
    const payload = Object.fromEntries(fd.entries());
    const editId  = document.getElementById('edit-member-id').value;
    const isEdit  = editId !== '';

    const url    = isEdit ? MB + '/api/v1/members/' + editId : MB + '/api/v1/members';
    const method = isEdit ? 'PUT' : 'POST';

    try {
        const res  = await fetch(url, { method, headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) });
        const data = await res.json();
        if (!res.ok || !data.success) { showFeedback(data.message || 'Failed to save member.', true); return; }
        showFeedback(isEdit ? 'Member updated successfully.' : ('Member created. Code: ' + (data.data?.member_code || '')), false);
        e.target.reset();
        document.getElementById('edit-member-id').value = '';
        await Promise.all([loadStats(), loadMembers()]);
        setTimeout(closeMemberModal, 1200);
    } catch(err) {
        showFeedback('Network error. Please try again.', true);
    }
});

/*  IMPORT MODAL  */
function openImportModal()  { document.getElementById('import-modal').classList.remove('hidden'); resetImportModal(); }
function closeImportModal() { document.getElementById('import-modal').classList.add('hidden'); }

document.getElementById('btn-open-import').addEventListener('click', openImportModal);
document.getElementById('btn-close-import').addEventListener('click', closeImportModal);
document.getElementById('btn-cancel-import').addEventListener('click', closeImportModal);
document.getElementById('import-modal-bg').addEventListener('click', closeImportModal);

function resetImportModal() {
    document.getElementById('import-file-input').value = '';
    document.getElementById('selected-filename').classList.add('hidden');
    document.getElementById('selected-filename').textContent = '';
    document.getElementById('btn-do-import').disabled = true;
    document.getElementById('import-result').classList.add('hidden');
    document.getElementById('import-errors').classList.add('hidden');
}

/* Template download */
document.getElementById('btn-download-template').addEventListener('click', () => {
    const header = 'member_code,first_name,last_name,gender,phone,email,date_of_birth,join_date,member_status,physical_address,ward,district,region,marital_status,baptism_date,notes';
    const sample = ',John,Doe,male,0712345678,john@example.com,1990-01-15,2024-01-01,active,123 Msasani St,Msasani,Kinondoni,Dar es Salaam,married,,';
    const blob = new Blob([header + '\n' + sample], { type: 'text/csv' });
    const a = Object.assign(document.createElement('a'), { href: URL.createObjectURL(blob), download: 'members_import_template.csv' });
    a.click();
});

/* File selection */
document.getElementById('import-file-input').addEventListener('change', e => {
    const f = e.target.files[0];
    if (!f) return;
    const fn = document.getElementById('selected-filename');
    fn.textContent = '&#128196; ' + f.name + ' (' + (f.size / 1024).toFixed(1) + ' KB)';
    fn.classList.remove('hidden');
    document.getElementById('btn-do-import').disabled = false;
    document.getElementById('import-result').classList.add('hidden');
    document.getElementById('import-errors').classList.add('hidden');
});

/* Drag and drop */
const dropZone = document.getElementById('drop-zone');
dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('border-royal-400', 'bg-royal-50/50'); });
dropZone.addEventListener('dragleave', () => dropZone.classList.remove('border-royal-400', 'bg-royal-50/50'));
dropZone.addEventListener('drop', e => {
    e.preventDefault();
    dropZone.classList.remove('border-royal-400', 'bg-royal-50/50');
    const f = e.dataTransfer.files[0];
    if (!f) return;
    const ext = f.name.split('.').pop().toLowerCase();
    if (!['csv','xlsx'].includes(ext)) { alert('Only .csv and .xlsx files are supported.'); return; }
    const dt = new DataTransfer();
    dt.items.add(f);
    document.getElementById('import-file-input').files = dt.files;
    document.getElementById('import-file-input').dispatchEvent(new Event('change'));
});

/* Do import */
document.getElementById('btn-do-import').addEventListener('click', async () => {
    const fileInput = document.getElementById('import-file-input');
    if (!fileInput.files.length) return;

    const btn = document.getElementById('btn-do-import');
    btn.disabled = true;
    btn.textContent = 'Importing';

    const fd = new FormData();
    fd.append('file', fileInput.files[0]);

    try {
        const res  = await fetch(MB + '/api/v1/members/import', { method: 'POST', body: fd });
        const data = await res.json();

        const resultEl = document.getElementById('import-result');
        const errorsEl = document.getElementById('import-errors');

        if (data.success) {
            resultEl.className = 'rounded-xl px-4 py-3 text-sm bg-emerald-50 border border-emerald-200 text-emerald-800';
            resultEl.innerHTML = '<strong>' + (data.message || 'Import complete.') + '</strong>';
            resultEl.classList.remove('hidden');
            await Promise.all([loadStats(), loadMembers()]);
        } else {
            resultEl.className = 'rounded-xl px-4 py-3 text-sm bg-red-50 border border-red-200 text-red-800';
            resultEl.innerHTML = '<strong>Import failed:</strong> ' + (data.message || 'Unknown error');
            resultEl.classList.remove('hidden');
        }

        const errs = data.data?.errors || [];
        if (errs.length) {
            errorsEl.innerHTML = '<strong class="block mb-1">Row Warnings:</strong>' + errs.map(e => '<div>' + e + '</div>').join('');
            errorsEl.classList.remove('hidden');
        }
    } catch(err) {
        const resultEl = document.getElementById('import-result');
        resultEl.className = 'rounded-xl px-4 py-3 text-sm bg-red-50 border border-red-200 text-red-800';
        resultEl.textContent = 'Network error. Please try again.';
        resultEl.classList.remove('hidden');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Import Members';
    }
});

/*  Bootstrap  */
Promise.all([loadStats(), loadMembers()]);
</script>
