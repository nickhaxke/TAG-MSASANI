@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-extrabold">Event Management</h2>
            <p class="text-slate-600">Plan events, assign tasks, track attendance, and monitor budgets.</p>
        </div>
        <button class="rounded-xl bg-cyan-700 text-white px-4 py-2 text-sm">Create Event</button>
    </div>

    <div class="grid lg:grid-cols-3 gap-4">
        <div class="lg:col-span-2 rounded-2xl bg-white p-4 border border-slate-200 shadow-sm">
            <h3 class="font-semibold">Event Planner</h3>
            <div class="grid md:grid-cols-2 gap-3 mt-3 text-sm">
                <input class="border rounded-xl px-3 py-2" placeholder="Event Title" />
                <select class="border rounded-xl px-3 py-2"><option>Category</option><option>Conference</option><option>Outreach</option></select>
                <input type="datetime-local" class="border rounded-xl px-3 py-2" />
                <input type="datetime-local" class="border rounded-xl px-3 py-2" />
                <input class="border rounded-xl px-3 py-2 md:col-span-2" placeholder="Venue" />
            </div>
        </div>
        <div class="rounded-2xl bg-white p-4 border border-slate-200 shadow-sm">
            <h3 class="font-semibold">Task Assignment</h3>
            <ul class="mt-3 space-y-2 text-sm">
                <li class="bg-slate-50 rounded-xl p-2">Sound setup · Assigned to Peter · Due 29 Mar</li>
                <li class="bg-slate-50 rounded-xl p-2">MC briefing · Assigned to Maria · Due 30 Mar</li>
            </ul>
        </div>
    </div>

    <div class="rounded-2xl bg-white p-4 border border-slate-200 shadow-sm overflow-x-auto">
        <h3 class="font-semibold mb-3">Event Budget vs Actual</h3>
        <table class="w-full text-sm">
            <thead><tr class="border-b text-left text-slate-500"><th class="py-2">Item</th><th>Planned</th><th>Actual</th><th>Variance</th></tr></thead>
            <tbody>
                <tr class="border-b"><td class="py-2">Venue</td><td>TZS 500,000</td><td>TZS 500,000</td><td>0</td></tr>
                <tr><td class="py-2">Publicity</td><td>TZS 300,000</td><td>TZS 270,000</td><td>+30,000</td></tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
