<?php $B = $baseUrl ?? ''; ?>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Communication</h1>
    <p class="text-gray-500 mt-1">Broadcast SMS and schedule event reminders to members and groups</p>
</div>

<!-- Features -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        </div>
        <h3 class="font-semibold text-gray-900 mb-1">SMS Broadcast</h3>
        <p class="text-sm text-gray-500">Send messages to all members or specific groups using Beem or Africa's Talking API</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <h3 class="font-semibold text-gray-900 mb-1">Event Reminders</h3>
        <p class="text-sm text-gray-500">Schedule automatic reminders before services and events</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
        </div>
        <h3 class="font-semibold text-gray-900 mb-1">Delivery Tracking</h3>
        <p class="text-sm text-gray-500">Track SMS delivery status with automatic retry for failures</p>
    </div>
</div>

<!-- Integration info -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
    <div class="flex items-start gap-3">
        <div class="w-10 h-10 rounded-xl bg-primary-50 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <h3 class="font-semibold text-gray-900 mb-2">SMS Integration Plan</h3>
            <ul class="space-y-2 text-sm text-gray-600">
                <li class="flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0"></span>
                    Send message requests to provider API (Beem or Africa's Talking)
                </li>
                <li class="flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0"></span>
                    Write each attempt to sms_logs for delivery auditing
                </li>
                <li class="flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0"></span>
                    Use cron job for retries where delivery_status = failed
                </li>
            </ul>
        </div>
    </div>
</div>
