@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-extrabold">Asset Management</h2>
        <p class="text-slate-600">Track equipment, assignments, locations, and maintenance history.</p>
    </div>

    <div class="grid md:grid-cols-2 gap-4">
        <div class="rounded-2xl bg-white p-4 border border-slate-200 shadow-sm">
            <h3 class="font-semibold">Register Asset</h3>
            <div class="grid gap-3 mt-3 text-sm">
                <input class="border rounded-xl px-3 py-2" placeholder="Asset Name" />
                <input class="border rounded-xl px-3 py-2" placeholder="Asset Tag" />
                <input class="border rounded-xl px-3 py-2" placeholder="Location" />
                <select class="border rounded-xl px-3 py-2"><option>Condition</option><option>Excellent</option><option>Good</option><option>Fair</option></select>
                <button class="rounded-xl bg-cyan-700 text-white px-4 py-2">Save Asset</button>
            </div>
        </div>
        <div class="rounded-2xl bg-white p-4 border border-slate-200 shadow-sm">
            <h3 class="font-semibold">Maintenance Log</h3>
            <ul class="mt-3 text-sm space-y-2">
                <li class="bg-slate-50 rounded-xl p-3">A/V Mixer · Repair · 26 Mar · TZS 180,000</li>
                <li class="bg-slate-50 rounded-xl p-3">Generator · Routine Service · 20 Mar · TZS 250,000</li>
            </ul>
        </div>
    </div>
</div>
@endsection
