@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-extrabold">Membership & Attendance</h2>
            <p class="text-slate-600">Register members, assign groups, and track service attendance.</p>
        </div>
        <button class="rounded-xl bg-cyan-700 text-white px-4 py-2 text-sm">Add Member</button>
    </div>

    <div class="rounded-2xl bg-white p-4 border border-slate-200 shadow-sm">
        <h3 class="font-semibold mb-3">Member Registration Form</h3>
        <div class="grid md:grid-cols-2 gap-3 text-sm">
            <input class="border rounded-xl px-3 py-2" placeholder="First Name" />
            <input class="border rounded-xl px-3 py-2" placeholder="Last Name" />
            <input class="border rounded-xl px-3 py-2" placeholder="Phone" />
            <select class="border rounded-xl px-3 py-2"><option>Gender</option><option>Male</option><option>Female</option></select>
            <select class="border rounded-xl px-3 py-2"><option>Assign Group</option><option>Youth</option><option>Choir</option></select>
            <input type="date" class="border rounded-xl px-3 py-2" />
        </div>
    </div>

    <div class="rounded-2xl bg-white p-4 border border-slate-200 shadow-sm overflow-x-auto">
        <h3 class="font-semibold mb-3">Recent Attendance (Sunday Service)</h3>
        <table class="w-full text-sm">
            <thead><tr class="border-b text-left text-slate-500"><th class="py-2">Member</th><th>Group</th><th>Status</th><th>Check-in</th></tr></thead>
            <tbody>
                <tr class="border-b"><td class="py-2">Neema Mushi</td><td>Youth</td><td>Present</td><td>08:13</td></tr>
                <tr><td class="py-2">Daniel Mhando</td><td>Choir</td><td>Late</td><td>08:40</td></tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
