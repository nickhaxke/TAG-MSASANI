<?php $B = $baseUrl ?? ''; ?>

<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Finance</h1>
        <p class="text-gray-500 mt-1">Record tithe, offerings, donations, and expenses</p>
    </div>
    <button onclick="document.getElementById('finance-modal').classList.remove('hidden')"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-xl shadow-sm transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
        Add Entry
    </button>
</div>

<!-- Finance entries table -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="font-semibold text-gray-800">Finance Entries</h2>
        <span id="finance-count" class="text-sm text-gray-500"></span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">Entry No</th>
                    <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">Date</th>
                    <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">Category</th>
                    <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">Type</th>
                    <th class="px-5 py-3 text-right font-semibold text-gray-600 uppercase tracking-wider text-xs">Amount</th>
                    <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">Method</th>
                    <th class="px-5 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider text-xs">Description</th>
                </tr>
            </thead>
            <tbody id="finance-tbody" class="divide-y divide-gray-100"></tbody>
        </table>
    </div>
    <div id="finance-empty" class="hidden px-5 py-12 text-center text-gray-400">
        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p class="font-medium">No finance entries yet</p>
        <p class="text-sm mt-1">Record your first income or expense</p>
    </div>
</div>

<!-- Add Finance Entry Modal -->
<div id="finance-modal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-gray-900/50" onclick="document.getElementById('finance-modal').classList.add('hidden')"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 z-10">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-gray-900">Add Finance Entry</h3>
                <button onclick="document.getElementById('finance-modal').classList.add('hidden')" class="p-1 hover:bg-gray-100 rounded-lg">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form id="finance-form" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Entry No</label>
                        <input name="entry_no" placeholder="FIN-2026-001" required class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                        <input type="date" name="entry_date" required class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select name="category_id" id="finance-category" required class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            <option value="">Loading...</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Amount (TZS)</label>
                        <input name="amount" type="number" step="0.01" placeholder="0.00" required class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                        <select name="payment_method" required class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            <option value="">Select...</option>
                            <option value="cash">Cash</option>
                            <option value="mobile_money">Mobile Money</option>
                            <option value="bank_transfer">Bank Transfer</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Source</label>
                        <select name="source_type" class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            <option value="manual">Manual</option>
                            <option value="event">Event</option>
                            <option value="procurement">Procurement</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <input name="description" placeholder="Brief description..." required class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="document.getElementById('finance-modal').classList.add('hidden')"
                            class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">Cancel</button>
                    <button type="submit"
                            class="px-6 py-2.5 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-xl shadow-sm transition-colors">Save Entry</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
async function loadFinanceEntries() {
    try {
        const res = await fetch(BASE_URL + '/api/v1/finance/entries');
        const data = await res.json();
        const rows = data.data || [];
        const tbody = document.getElementById('finance-tbody');
        const empty = document.getElementById('finance-empty');
        const count = document.getElementById('finance-count');

        if (rows.length === 0) {
            tbody.innerHTML = '';
            empty.classList.remove('hidden');
            count.textContent = '0 entries';
            return;
        }
        empty.classList.add('hidden');
        count.textContent = rows.length + ' entr' + (rows.length !== 1 ? 'ies' : 'y');

        const tc = { income: 'bg-green-100 text-green-800', expense: 'bg-red-100 text-red-800' };
        tbody.innerHTML = rows.map(r => `
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-5 py-3 font-mono text-gray-600 text-xs">${r.entry_no}</td>
                <td class="px-5 py-3 text-gray-600 text-xs">${r.entry_date}</td>
                <td class="px-5 py-3 text-gray-900 font-medium">${r.category_name || '-'}</td>
                <td class="px-5 py-3"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${tc[r.category_type]||'bg-gray-100 text-gray-800'}">${r.category_type || '-'}</span></td>
                <td class="px-5 py-3 text-right font-semibold ${r.category_type==='income' ? 'text-green-600' : 'text-red-600'}">TZS ${Number(r.amount).toLocaleString()}</td>
                <td class="px-5 py-3 text-gray-600 capitalize">${(r.payment_method||'').replace('_',' ')}</td>
                <td class="px-5 py-3 text-gray-600 truncate max-w-[200px]">${r.description}</td>
            </tr>
        `).join('');
    } catch (e) {
        console.error('Failed to load finance entries:', e);
    }
}

async function loadCategories() {
    try {
        const res = await fetch(BASE_URL + '/api/v1/finance/categories');
        const data = await res.json();
        const sel = document.getElementById('finance-category');
        sel.innerHTML = '<option value="">Select category...</option>';
        (data.data || []).forEach(c => {
            sel.innerHTML += `<option value="${c.id}">${c.name} (${c.category_type})</option>`;
        });
    } catch (e) {
        console.error('Failed to load categories:', e);
    }
}

document.getElementById('finance-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const payload = Object.fromEntries(new FormData(this).entries());
    try {
        const res = await fetch(BASE_URL + '/api/v1/finance/entries', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
        const data = await res.json();
        if (res.ok) {
            this.reset();
            document.getElementById('finance-modal').classList.add('hidden');
            loadFinanceEntries();
        } else {
            alert(data.message || 'Error');
        }
    } catch (err) {
        alert('Network error');
    }
});

loadFinanceEntries();
loadCategories();
</script>
