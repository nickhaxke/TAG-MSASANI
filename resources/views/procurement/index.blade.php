@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-extrabold">Procurement System</h2>
        <p class="text-slate-600">Manage request, approval workflow, suppliers, and purchase orders.</p>
    </div>

    <div class="rounded-2xl bg-white p-4 border border-slate-200 shadow-sm">
        <h3 class="font-semibold mb-3">New Purchase Request</h3>
        <div class="grid md:grid-cols-2 gap-3 text-sm">
            <input class="border rounded-xl px-3 py-2" placeholder="Purpose" />
            <input class="border rounded-xl px-3 py-2" placeholder="Estimated Cost (TZS)" />
            <input type="date" class="border rounded-xl px-3 py-2" />
            <input type="date" class="border rounded-xl px-3 py-2" />
            <select class="border rounded-xl px-3 py-2 md:col-span-2"><option>Link Event (optional)</option></select>
        </div>
        <button class="mt-3 rounded-xl bg-cyan-700 text-white px-4 py-2 text-sm">Submit for Approval</button>
    </div>

    <div class="rounded-2xl bg-white p-4 border border-slate-200 shadow-sm overflow-x-auto">
        <h3 class="font-semibold mb-3">Approval Queue</h3>
        <table class="w-full text-sm">
            <thead><tr class="border-b text-left text-slate-500"><th class="py-2">Request No</th><th>Purpose</th><th>Amount</th><th>Status</th><th>Action</th></tr></thead>
            <tbody>
                <tr class="border-b"><td class="py-2">PR-2026-031</td><td>Choir Uniforms</td><td>TZS 1,200,000</td><td>Submitted</td><td><button class="text-cyan-700">Approve</button></td></tr>
                <tr><td class="py-2">PR-2026-032</td><td>Projector Repair</td><td>TZS 350,000</td><td>Approved</td><td><button class="text-cyan-700">Create PO</button></td></tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
