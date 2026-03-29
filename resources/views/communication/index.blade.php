@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-extrabold">Communication</h2>
        <p class="text-slate-600">Send SMS broadcasts and event reminders to members and groups.</p>
    </div>

    <div class="grid md:grid-cols-2 gap-4">
        <div class="rounded-2xl bg-white p-4 border border-slate-200 shadow-sm">
            <h3 class="font-semibold">SMS Broadcast</h3>
            <div class="grid gap-3 mt-3 text-sm">
                <select class="border rounded-xl px-3 py-2"><option>Target</option><option>All Members</option><option>Youth Group</option><option>Choir</option></select>
                <textarea class="border rounded-xl px-3 py-2 h-28" placeholder="Write message (max 480 chars)"></textarea>
                <button class="rounded-xl bg-cyan-700 text-white px-4 py-2">Send SMS</button>
            </div>
        </div>
        <div class="rounded-2xl bg-white p-4 border border-slate-200 shadow-sm">
            <h3 class="font-semibold">Event Reminder</h3>
            <div class="grid gap-3 mt-3 text-sm">
                <select class="border rounded-xl px-3 py-2"><option>Select Event</option></select>
                <textarea class="border rounded-xl px-3 py-2 h-28" placeholder="Reminder text"></textarea>
                <button class="rounded-xl border border-cyan-700 text-cyan-700 px-4 py-2">Queue Reminder</button>
            </div>
        </div>
    </div>
</div>
@endsection
