<?php $B = $baseUrl ?? ''; ?>

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div>
        <h1 class="text-3xl font-heading font-semibold text-royal-900">Asset Operations Center</h1>
        <p class="text-mist-600 text-sm mt-0.5">Full lifecycle tracking: register, assign, monitor condition, and log maintenance costs.</p>
    </div>
    <a href="<?= $B ?>/finance" class="px-4 py-2 rounded-xl bg-emerald-600 text-white hover:bg-emerald-700 text-sm font-semibold">Open Finance Module</a>
</div>

<section class="grid grid-cols-2 lg:grid-cols-5 gap-3 mb-6">
    <div class="bg-white rounded-2xl border border-mist-200 p-4">
        <p class="text-xs uppercase tracking-wider text-mist-500">Total Assets</p>
        <p id="stat-total" class="text-2xl font-bold text-royal-800 mt-1">0</p>
    </div>
    <div class="bg-white rounded-2xl border border-mist-200 p-4">
        <p class="text-xs uppercase tracking-wider text-emerald-600">Active</p>
        <p id="stat-active" class="text-2xl font-bold text-emerald-700 mt-1">0</p>
    </div>
    <div class="bg-white rounded-2xl border border-mist-200 p-4">
        <p class="text-xs uppercase tracking-wider text-dawn-600">Due Maintenance</p>
        <p id="stat-due" class="text-2xl font-bold text-dawn-700 mt-1">0</p>
    </div>
    <div class="bg-white rounded-2xl border border-mist-200 p-4">
        <p class="text-xs uppercase tracking-wider text-red-600">Poor / Retired</p>
        <p id="stat-risk" class="text-2xl font-bold text-red-700 mt-1">0</p>
    </div>
    <div class="bg-white rounded-2xl border border-mist-200 p-4">
        <p class="text-xs uppercase tracking-wider text-royal-600">Total Value</p>
        <p id="stat-value" class="text-xl font-bold text-royal-800 mt-1">TZS 0</p>
    </div>
</section>

<section class="grid grid-cols-1 xl:grid-cols-3 gap-4 mb-6">
    <article class="xl:col-span-2 bg-white rounded-2xl border border-mist-200 shadow-sm p-5">
        <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
            <h2 id="asset-form-title" class="text-lg font-heading font-semibold text-royal-800">Register New Asset</h2>
            <button id="btn-reset-asset-form" class="px-3 py-1.5 rounded-lg bg-mist-100 text-mist-700 hover:bg-mist-200 text-xs font-semibold">Reset Form</button>
        </div>

        <form id="asset-form" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3">
            <input type="hidden" name="asset_id" value="">

            <div>
                <label class="block text-xs font-semibold text-mist-600 mb-1">Asset Name</label>
                <input name="name" required placeholder="Yamaha Mixer, Generator, Chair Set" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-mist-600 mb-1">Asset Tag</label>
                <input name="asset_tag" placeholder="Auto-generated if blank" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm font-mono">
            </div>
            <div>
                <label class="block text-xs font-semibold text-mist-600 mb-1">Category</label>
                <input list="asset-category-list" name="category" required placeholder="Sound, Furniture, Vehicle" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                <datalist id="asset-category-list">
                    <option value="Sound Equipment"></option>
                    <option value="Furniture"></option>
                    <option value="Musical Instrument"></option>
                    <option value="Vehicle"></option>
                    <option value="Electrical"></option>
                    <option value="IT Equipment"></option>
                </datalist>
            </div>

            <div>
                <label class="block text-xs font-semibold text-mist-600 mb-1">Current Location</label>
                <input name="current_location" required placeholder="Main Hall, Store Room, Office" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-mist-600 mb-1">Condition</label>
                <select name="condition_status" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                    <option value="excellent">Excellent</option>
                    <option value="good" selected>Good</option>
                    <option value="fair">Fair</option>
                    <option value="poor">Poor</option>
                    <option value="retired">Retired</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-mist-600 mb-1">Status</label>
                <select name="is_active" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-mist-600 mb-1">Purchase Date</label>
                <input type="date" name="purchase_date" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-mist-600 mb-1">Purchase Value (TZS)</label>
                <input type="number" step="0.01" min="0" name="purchase_value" placeholder="0.00" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-mist-600 mb-1">Warranty Expiry</label>
                <input type="date" name="warranty_expiry" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
            </div>

            <div>
                <label class="block text-xs font-semibold text-mist-600 mb-1">Assigned User</label>
                <select id="asset-assigned-user" name="assigned_to_user_id" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                    <option value="">Not assigned</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-mist-600 mb-1">Assigned Event</label>
                <select id="asset-assigned-event" name="assigned_event_id" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                    <option value="">Not assigned</option>
                </select>
            </div>
            <div class="md:col-span-2 xl:col-span-1">
                <label class="block text-xs font-semibold text-mist-600 mb-1">Notes</label>
                <textarea name="notes" rows="2" placeholder="Optional notes" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm"></textarea>
            </div>

            <div class="md:col-span-2 xl:col-span-3 flex justify-end gap-2 pt-2 border-t border-mist-100">
                <button type="button" id="btn-cancel-edit" class="px-4 py-2.5 rounded-xl bg-mist-100 text-mist-700 hover:bg-mist-200 text-sm font-medium hidden">Cancel Edit</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-royal-600 text-white hover:bg-royal-700 text-sm font-semibold">Save Asset</button>
            </div>
        </form>

        <div id="asset-form-feedback" class="hidden mt-3 rounded-xl px-3 py-2 text-sm"></div>
    </article>

    <article class="bg-white rounded-2xl border border-mist-200 shadow-sm p-5">
        <h2 class="text-lg font-heading font-semibold text-royal-800 mb-4">Maintenance Log</h2>
        <form id="maintenance-form" class="space-y-3">
            <div>
                <label class="block text-xs font-semibold text-mist-600 mb-1">Asset</label>
                <select id="maintenance-asset-id" name="asset_id" required class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                    <option value="">Select asset</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-mist-600 mb-1">Maintenance Type</label>
                <select name="maintenance_type" required class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                    <option value="routine">Routine</option>
                    <option value="repair">Repair</option>
                    <option value="inspection">Inspection</option>
                    <option value="replacement">Replacement</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-mist-600 mb-1">Maintenance Date</label>
                <input type="date" name="maintenance_date" required class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-mist-600 mb-1">Next Due Date</label>
                <input type="date" name="next_due_date" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-mist-600 mb-1">Cost (TZS)</label>
                <input type="number" min="0" step="0.01" name="maintenance_cost" value="0" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-mist-600 mb-1">Service Provider</label>
                <input name="service_provider" placeholder="Vendor / Technician" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-mist-600 mb-1">Issue Description</label>
                <textarea name="issue_description" rows="2" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm" placeholder="What issue was found?"></textarea>
            </div>
            <div>
                <label class="block text-xs font-semibold text-mist-600 mb-1">Action Taken</label>
                <textarea name="action_taken" required rows="2" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm" placeholder="What was fixed or serviced?"></textarea>
            </div>
            <div>
                <label class="block text-xs font-semibold text-mist-600 mb-1">Update Asset Condition</label>
                <select name="condition_status" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                    <option value="">No change</option>
                    <option value="excellent">Excellent</option>
                    <option value="good">Good</option>
                    <option value="fair">Fair</option>
                    <option value="poor">Poor</option>
                    <option value="retired">Retired</option>
                </select>
            </div>

            <button type="submit" class="w-full px-4 py-2.5 rounded-xl bg-emerald-600 text-white hover:bg-emerald-700 text-sm font-semibold">Save Maintenance Log</button>
        </form>
        <div id="maintenance-feedback" class="hidden mt-3 rounded-xl px-3 py-2 text-sm"></div>
    </article>
</section>

<section class="bg-white rounded-2xl border border-mist-200 shadow-sm overflow-hidden mb-6">
    <div class="px-5 py-4 border-b border-mist-100 flex flex-wrap items-center justify-between gap-2">
        <h2 class="font-semibold text-royal-800">Asset Register</h2>
        <div class="flex flex-wrap gap-2">
            <input id="asset-search" type="text" placeholder="Search tag, name, location" class="rounded-xl border border-mist-200 px-3 py-2 text-sm">
            <select id="asset-condition-filter" class="rounded-xl border border-mist-200 px-3 py-2 text-sm">
                <option value="">All conditions</option>
                <option value="excellent">Excellent</option>
                <option value="good">Good</option>
                <option value="fair">Fair</option>
                <option value="poor">Poor</option>
                <option value="retired">Retired</option>
            </select>
            <select id="asset-category-filter" class="rounded-xl border border-mist-200 px-3 py-2 text-sm">
                <option value="">All categories</option>
            </select>
            <button id="btn-asset-filter" class="px-3 py-2 rounded-xl bg-mist-100 text-mist-700 hover:bg-mist-200 text-sm">Apply</button>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-mist-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-mist-500">Tag</th>
                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-mist-500">Asset</th>
                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-mist-500">Category</th>
                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-mist-500">Condition</th>
                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-mist-500">Location</th>
                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-mist-500">Assigned</th>
                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-mist-500">Value</th>
                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-mist-500">Next Due</th>
                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-mist-500">Actions</th>
                </tr>
            </thead>
            <tbody id="assets-body" class="divide-y divide-mist-100"></tbody>
        </table>
    </div>
    <div id="assets-empty" class="hidden px-5 py-10 text-center text-mist-500">No assets found for current filters.</div>
</section>

<section class="bg-white rounded-2xl border border-mist-200 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-mist-100 flex flex-wrap items-center justify-between gap-2">
        <h2 class="font-semibold text-royal-800">Maintenance History (Selected Asset)</h2>
        <span id="maintenance-title" class="text-xs text-mist-500">Select an asset to view history</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-mist-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-mist-500">Date</th>
                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-mist-500">Type</th>
                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-mist-500">Action</th>
                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-mist-500">Provider</th>
                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-mist-500">Cost</th>
                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-mist-500">Next Due</th>
                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-mist-500">By</th>
                </tr>
            </thead>
            <tbody id="maintenance-body" class="divide-y divide-mist-100"></tbody>
        </table>
    </div>
    <div id="maintenance-empty" class="px-5 py-10 text-center text-mist-500">No maintenance records yet.</div>
</section>

<script>
let assetsState = {
    rows: [],
    users: [],
    events: [],
    selectedAssetId: null,
};

function money(v) {
    return 'TZS ' + Number(v || 0).toLocaleString();
}

function conditionClass(status) {
    const map = {
        excellent: 'bg-emerald-100 text-emerald-700',
        good: 'bg-blue-100 text-blue-700',
        fair: 'bg-amber-100 text-amber-700',
        poor: 'bg-red-100 text-red-700',
        retired: 'bg-mist-100 text-mist-600',
    };
    return map[status] || 'bg-mist-100 text-mist-600';
}

function setFeedback(elId, message, isError = false) {
    const el = document.getElementById(elId);
    el.classList.remove('hidden');
    el.textContent = message;
    el.className = `mt-3 rounded-xl px-3 py-2 text-sm ${isError ? 'bg-red-50 text-red-700 border border-red-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200'}`;
}

async function loadAssetsOverview() {
    const res = await fetch(BASE_URL + '/api/v1/assets/overview');
    const payload = await res.json();
    if (!res.ok || !payload.success) {
        throw new Error(payload.message || 'Failed to load assets overview');
    }

    const data = payload.data || {};
    document.getElementById('stat-total').textContent = String(data.total_assets || 0);
    document.getElementById('stat-active').textContent = String(data.active_count || 0);
    document.getElementById('stat-due').textContent = String(data.due_maintenance || 0);
    document.getElementById('stat-risk').textContent = String(Number(data.conditions?.poor || 0) + Number(data.conditions?.retired || 0));
    document.getElementById('stat-value').textContent = money(data.total_value || 0);
}

async function loadMeta() {
    const [usersRes, eventsRes] = await Promise.all([
        fetch(BASE_URL + '/api/v1/meta/users'),
        fetch(BASE_URL + '/api/v1/events'),
    ]);
    const usersPayload = await usersRes.json();
    const eventsPayload = await eventsRes.json();
    assetsState.users = usersPayload.data || [];
    assetsState.events = eventsPayload.data || [];

    const userSelect = document.getElementById('asset-assigned-user');
    userSelect.innerHTML = '<option value="">Not assigned</option>' + assetsState.users.map((u) => `<option value="${u.id}">${u.full_name}</option>`).join('');

    const eventSelect = document.getElementById('asset-assigned-event');
    eventSelect.innerHTML = '<option value="">Not assigned</option>' + assetsState.events.map((e) => `<option value="${e.id}">${e.title}</option>`).join('');
}

function rebuildCategoryFilter() {
    const categories = [...new Set(assetsState.rows.map((r) => r.category).filter(Boolean))].sort();
    const sel = document.getElementById('asset-category-filter');
    const current = sel.value;
    sel.innerHTML = '<option value="">All categories</option>' + categories.map((c) => `<option value="${c}">${c}</option>`).join('');
    sel.value = categories.includes(current) ? current : '';
}

function refreshMaintenanceAssetDropdown() {
    const sel = document.getElementById('maintenance-asset-id');
    const current = String(assetsState.selectedAssetId || sel.value || '');
    sel.innerHTML = '<option value="">Select asset</option>' + assetsState.rows.map((r) => `<option value="${r.id}">${r.asset_tag} - ${r.name}</option>`).join('');
    if (current) {
        sel.value = current;
    }
}

async function loadAssets() {
    const search = document.getElementById('asset-search').value.trim();
    const condition = document.getElementById('asset-condition-filter').value;
    const category = document.getElementById('asset-category-filter').value;

    const params = new URLSearchParams();
    if (search) params.set('search', search);
    if (condition) params.set('condition', condition);
    if (category) params.set('category', category);

    const res = await fetch(BASE_URL + '/api/v1/assets?' + params.toString());
    const payload = await res.json();
    if (!res.ok || !payload.success) {
        throw new Error(payload.message || 'Failed to load assets list');
    }

    assetsState.rows = payload.data || [];
    rebuildCategoryFilter();
    refreshMaintenanceAssetDropdown();
    renderAssetsTable();
}

function renderAssetsTable() {
    const body = document.getElementById('assets-body');
    const empty = document.getElementById('assets-empty');

    if (!assetsState.rows.length) {
        body.innerHTML = '';
        empty.classList.remove('hidden');
        return;
    }
    empty.classList.add('hidden');

    body.innerHTML = assetsState.rows.map((row) => {
        const assigned = row.assigned_user_name || row.assigned_event_title || '-';
        return `
            <tr class="hover:bg-mist-50/60">
                <td class="px-4 py-3 text-xs font-mono text-mist-700">${row.asset_tag}</td>
                <td class="px-4 py-3">
                    <p class="font-semibold text-royal-800">${row.name}</p>
                    <p class="text-[11px] text-mist-500">${row.is_active == 1 ? 'Active' : 'Inactive'}</p>
                </td>
                <td class="px-4 py-3 text-mist-600">${row.category}</td>
                <td class="px-4 py-3"><span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold ${conditionClass(row.condition_status)}">${row.condition_status}</span></td>
                <td class="px-4 py-3 text-mist-600">${row.current_location || '-'}</td>
                <td class="px-4 py-3 text-mist-600 text-xs">${assigned}</td>
                <td class="px-4 py-3 text-mist-700">${money(row.purchase_value || 0)}</td>
                <td class="px-4 py-3 text-mist-600 text-xs">${row.latest_next_due_date || '-'}</td>
                <td class="px-4 py-3">
                    <div class="flex flex-wrap gap-2">
                        <button class="px-2 py-1 rounded-lg bg-royal-100 text-royal-700 text-xs font-semibold btn-edit" data-id="${row.id}">Edit</button>
                        <button class="px-2 py-1 rounded-lg bg-emerald-100 text-emerald-700 text-xs font-semibold btn-maintain" data-id="${row.id}">Maintenance</button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');

    document.querySelectorAll('.btn-edit').forEach((btn) => {
        btn.addEventListener('click', () => {
            const id = Number(btn.dataset.id);
            openAssetEdit(id);
        });
    });

    document.querySelectorAll('.btn-maintain').forEach((btn) => {
        btn.addEventListener('click', () => {
            const id = Number(btn.dataset.id);
            focusMaintenance(id);
        });
    });
}

function clearAssetForm() {
    const form = document.getElementById('asset-form');
    form.reset();
    form.querySelector('[name="asset_id"]').value = '';
    form.querySelector('[name="condition_status"]').value = 'good';
    form.querySelector('[name="is_active"]').value = '1';
    document.getElementById('asset-form-title').textContent = 'Register New Asset';
    document.getElementById('btn-cancel-edit').classList.add('hidden');
    document.getElementById('asset-form-feedback').classList.add('hidden');
}

function openAssetEdit(id) {
    const row = assetsState.rows.find((x) => Number(x.id) === id);
    if (!row) return;
    const form = document.getElementById('asset-form');
    form.querySelector('[name="asset_id"]').value = String(row.id);
    form.querySelector('[name="name"]').value = row.name || '';
    form.querySelector('[name="asset_tag"]').value = row.asset_tag || '';
    form.querySelector('[name="category"]').value = row.category || '';
    form.querySelector('[name="purchase_date"]').value = row.purchase_date || '';
    form.querySelector('[name="purchase_value"]').value = row.purchase_value || '';
    form.querySelector('[name="condition_status"]').value = row.condition_status || 'good';
    form.querySelector('[name="current_location"]').value = row.current_location || '';
    form.querySelector('[name="assigned_to_user_id"]').value = row.assigned_to_user_id || '';
    form.querySelector('[name="assigned_event_id"]').value = row.assigned_event_id || '';
    form.querySelector('[name="warranty_expiry"]').value = row.warranty_expiry || '';
    form.querySelector('[name="is_active"]').value = row.is_active == 1 ? '1' : '0';
    form.querySelector('[name="notes"]').value = row.notes || '';

    document.getElementById('asset-form-title').textContent = 'Edit Asset - ' + row.asset_tag;
    document.getElementById('btn-cancel-edit').classList.remove('hidden');
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

async function submitAssetForm(e) {
    e.preventDefault();
    const form = e.currentTarget;
    const fd = new FormData(form);
    const payload = Object.fromEntries(fd.entries());
    const assetId = payload.asset_id;
    delete payload.asset_id;

    const method = assetId ? 'PUT' : 'POST';
    const url = assetId ? BASE_URL + '/api/v1/assets/' + assetId : BASE_URL + '/api/v1/assets';

    const res = await fetch(url, {
        method,
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload),
    });
    const data = await res.json();

    if (!res.ok || !data.success) {
        setFeedback('asset-form-feedback', data.message || 'Failed to save asset', true);
        return;
    }

    setFeedback('asset-form-feedback', assetId ? 'Asset updated successfully.' : 'Asset registered successfully.');
    clearAssetForm();
    await Promise.all([loadAssetsOverview(), loadAssets()]);
}

function focusMaintenance(assetId) {
    assetsState.selectedAssetId = assetId;
    document.getElementById('maintenance-asset-id').value = String(assetId);
    loadMaintenance(assetId).catch((error) => {
        setFeedback('maintenance-feedback', error.message || 'Failed to load maintenance history', true);
    });
    document.getElementById('maintenance-form').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

async function loadMaintenance(assetId) {
    if (!assetId) {
        document.getElementById('maintenance-title').textContent = 'Select an asset to view history';
        document.getElementById('maintenance-body').innerHTML = '';
        document.getElementById('maintenance-empty').classList.remove('hidden');
        return;
    }

    const row = assetsState.rows.find((x) => Number(x.id) === Number(assetId));
    if (row) {
        document.getElementById('maintenance-title').textContent = row.asset_tag + ' - ' + row.name;
    }

    const res = await fetch(BASE_URL + '/api/v1/assets/' + assetId + '/maintenance');
    const payload = await res.json();
    if (!res.ok || !payload.success) {
        throw new Error(payload.message || 'Failed to load maintenance logs');
    }

    const rows = payload.data || [];
    const body = document.getElementById('maintenance-body');
    const empty = document.getElementById('maintenance-empty');

    if (!rows.length) {
        body.innerHTML = '';
        empty.classList.remove('hidden');
        return;
    }

    empty.classList.add('hidden');
    body.innerHTML = rows.map((log) => `
        <tr class="hover:bg-mist-50/60">
            <td class="px-4 py-3 text-mist-600">${log.maintenance_date}</td>
            <td class="px-4 py-3 text-xs text-royal-700 font-semibold uppercase">${log.maintenance_type}</td>
            <td class="px-4 py-3 text-mist-700">${log.action_taken || '-'}</td>
            <td class="px-4 py-3 text-mist-600">${log.service_provider || '-'}</td>
            <td class="px-4 py-3 text-mist-700">${money(log.maintenance_cost || 0)}</td>
            <td class="px-4 py-3 text-mist-600">${log.next_due_date || '-'}</td>
            <td class="px-4 py-3 text-mist-600 text-xs">${log.created_by_name || '-'}</td>
        </tr>
    `).join('');
}

async function submitMaintenanceForm(e) {
    e.preventDefault();
    const form = e.currentTarget;
    const fd = new FormData(form);
    const payload = Object.fromEntries(fd.entries());
    const assetId = payload.asset_id;

    if (!assetId) {
        setFeedback('maintenance-feedback', 'Please select an asset first.', true);
        return;
    }

    delete payload.asset_id;
    const res = await fetch(BASE_URL + '/api/v1/assets/' + assetId + '/maintenance', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload),
    });
    const data = await res.json();

    if (!res.ok || !data.success) {
        setFeedback('maintenance-feedback', data.message || 'Failed to save maintenance log', true);
        return;
    }

    setFeedback('maintenance-feedback', 'Maintenance log saved successfully.');
    form.reset();
    form.querySelector('[name="maintenance_type"]').value = 'routine';
    form.querySelector('[name="maintenance_cost"]').value = '0';
    form.querySelector('[name="asset_id"]').value = String(assetId);
    await Promise.all([loadAssetsOverview(), loadAssets(), loadMaintenance(assetId)]);
}

document.getElementById('asset-form').addEventListener('submit', submitAssetForm);
document.getElementById('maintenance-form').addEventListener('submit', submitMaintenanceForm);

document.getElementById('btn-reset-asset-form').addEventListener('click', clearAssetForm);
document.getElementById('btn-cancel-edit').addEventListener('click', clearAssetForm);

document.getElementById('btn-asset-filter').addEventListener('click', () => {
    loadAssets().catch((error) => {
        setFeedback('asset-form-feedback', error.message || 'Failed to load assets', true);
    });
});

document.getElementById('asset-search').addEventListener('input', () => {
    loadAssets().catch(() => {});
});
document.getElementById('asset-condition-filter').addEventListener('change', () => {
    loadAssets().catch(() => {});
});
document.getElementById('asset-category-filter').addEventListener('change', () => {
    loadAssets().catch(() => {});
});

document.getElementById('maintenance-asset-id').addEventListener('change', (e) => {
    const assetId = Number(e.target.value || 0);
    assetsState.selectedAssetId = assetId || null;
    loadMaintenance(assetId || null).catch((error) => {
        setFeedback('maintenance-feedback', error.message || 'Failed to load maintenance history', true);
    });
});

document.querySelector('#maintenance-form [name="maintenance_date"]').value = new Date().toISOString().slice(0, 10);

Promise.all([loadAssetsOverview(), loadMeta(), loadAssets()])
    .then(() => loadMaintenance(null))
    .catch((error) => {
        console.error('Assets page bootstrap failed', error);
        setFeedback('asset-form-feedback', error.message || 'Failed to initialize assets page', true);
    });
</script>
