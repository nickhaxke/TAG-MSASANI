@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-extrabold">Reports Center</h2>
        <p class="text-slate-600">Financial, attendance, event, procurement, and asset analytics.</p>
    </div>

    <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-4 text-sm">
        <article class="rounded-2xl bg-white p-4 border border-slate-200 shadow-sm"><h3 class="font-semibold">Financial Reports</h3><p class="text-slate-500 mt-2">Daily, monthly, yearly statements with export options.</p></article>
        <article class="rounded-2xl bg-white p-4 border border-slate-200 shadow-sm"><h3 class="font-semibold">Attendance Reports</h3><p class="text-slate-500 mt-2">Services and event attendance by demographics/groups.</p></article>
        <article class="rounded-2xl bg-white p-4 border border-slate-200 shadow-sm"><h3 class="font-semibold">Event Reports</h3><p class="text-slate-500 mt-2">Budget vs actual and turnout performance summaries.</p></article>
        <article class="rounded-2xl bg-white p-4 border border-slate-200 shadow-sm"><h3 class="font-semibold">Procurement Reports</h3><p class="text-slate-500 mt-2">Request pipeline, supplier spend, approval turnaround.</p></article>
        <article class="rounded-2xl bg-white p-4 border border-slate-200 shadow-sm"><h3 class="font-semibold">Asset Reports</h3><p class="text-slate-500 mt-2">Asset condition, assignment, and maintenance costs.</p></article>
    </div>
</div>
@endsection
