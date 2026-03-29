@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-extrabold">Finance Management</h2>
        <p class="text-slate-600">Capture tithe, offerings, donations, and procurement-linked expenses.</p>
    </div>

    <div class="grid md:grid-cols-2 gap-4">
        <div class="rounded-2xl bg-white p-4 border border-slate-200 shadow-sm">
            <h3 class="font-semibold">Record Income / Expense</h3>
            <div class="grid gap-3 mt-3 text-sm">
                <select class="border rounded-xl px-3 py-2"><option>Entry Type</option><option>Income</option><option>Expense</option></select>
                <select class="border rounded-xl px-3 py-2"><option>Category</option><option>Tithe</option><option>Offering</option><option>Donation</option><option>Procurement</option></select>
                <input class="border rounded-xl px-3 py-2" placeholder="Amount (TZS)" />
                <select class="border rounded-xl px-3 py-2"><option>Payment Method</option><option>Cash</option><option>Mobile Money</option><option>Bank Transfer</option></select>
                <button class="rounded-xl bg-cyan-700 text-white px-4 py-2">Save Entry</button>
            </div>
        </div>
        <div class="rounded-2xl bg-white p-4 border border-slate-200 shadow-sm">
            <h3 class="font-semibold">Financial Reports</h3>
            <div class="mt-3 space-y-2 text-sm">
                <button class="w-full text-left bg-slate-50 rounded-xl p-3">Daily Statement</button>
                <button class="w-full text-left bg-slate-50 rounded-xl p-3">Monthly Summary</button>
                <button class="w-full text-left bg-slate-50 rounded-xl p-3">Yearly Summary</button>
                <div class="grid grid-cols-2 gap-2 pt-2">
                    <button class="rounded-xl border px-3 py-2">Export PDF</button>
                    <button class="rounded-xl border px-3 py-2">Export Excel</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
