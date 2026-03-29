@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-3">
        <div>
            <h2 class="text-2xl md:text-3xl font-extrabold">Church Dashboard</h2>
            <p class="text-slate-600">Quick oversight of members, giving, events, and expenses.</p>
        </div>
        <button class="rounded-xl bg-cyan-700 text-white px-4 py-2 text-sm hover:bg-cyan-800">New Event</button>
    </div>

    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <article class="rounded-2xl bg-white p-4 shadow-sm border border-slate-200">
            <p class="text-sm text-slate-500">Total Members</p>
            <p class="mt-2 text-3xl font-bold">2,486</p>
            <p class="text-xs text-emerald-600 mt-1">+52 this month</p>
        </article>
        <article class="rounded-2xl bg-white p-4 shadow-sm border border-slate-200">
            <p class="text-sm text-slate-500">Monthly Income</p>
            <p class="mt-2 text-3xl font-bold">TZS 18.4M</p>
            <p class="text-xs text-emerald-600 mt-1">+12% vs last month</p>
        </article>
        <article class="rounded-2xl bg-white p-4 shadow-sm border border-slate-200">
            <p class="text-sm text-slate-500">Upcoming Events</p>
            <p class="mt-2 text-3xl font-bold">7</p>
            <p class="text-xs text-slate-500 mt-1">3 with pending tasks</p>
        </article>
        <article class="rounded-2xl bg-white p-4 shadow-sm border border-slate-200">
            <p class="text-sm text-slate-500">Monthly Expenses</p>
            <p class="mt-2 text-3xl font-bold">TZS 9.7M</p>
            <p class="text-xs text-amber-600 mt-1">Procurement linked: 84%</p>
        </article>
    </section>

    <section class="grid gap-4 lg:grid-cols-5">
        <div class="lg:col-span-3 rounded-2xl bg-white p-4 border border-slate-200 shadow-sm">
            <h3 class="font-semibold">Recent Financial Activity</h3>
            <div class="overflow-x-auto mt-3">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-slate-500 border-b">
                            <th class="py-2">Date</th>
                            <th class="py-2">Type</th>
                            <th class="py-2">Category</th>
                            <th class="py-2">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b"><td class="py-2">2026-03-27</td><td>Income</td><td>Offering</td><td>TZS 1,240,000</td></tr>
                        <tr class="border-b"><td class="py-2">2026-03-27</td><td>Expense</td><td>Procurement</td><td>TZS 780,000</td></tr>
                        <tr><td class="py-2">2026-03-26</td><td>Income</td><td>Tithe</td><td>TZS 2,050,000</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="lg:col-span-2 rounded-2xl bg-white p-4 border border-slate-200 shadow-sm">
            <h3 class="font-semibold">Upcoming Events</h3>
            <ul class="mt-3 space-y-3 text-sm">
                <li class="rounded-xl bg-slate-50 p-3"><p class="font-semibold">Youth Revival Night</p><p class="text-slate-500">30 Mar · 18:00 · Main Hall</p></li>
                <li class="rounded-xl bg-slate-50 p-3"><p class="font-semibold">Choir Outreach</p><p class="text-slate-500">02 Apr · 09:00 · Kijitonyama</p></li>
                <li class="rounded-xl bg-slate-50 p-3"><p class="font-semibold">Leadership Workshop</p><p class="text-slate-500">05 Apr · 10:00 · Office Block</p></li>
            </ul>
        </div>
    </section>
</div>
@endsection
