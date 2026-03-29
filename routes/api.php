<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AssetController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommunicationController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\FinanceController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\ProcurementController;
use App\Http\Controllers\Api\ReportController;

Route::prefix('v1')->group(function () {
    Route::post('/auth/login', [AuthController::class, 'login']);

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/dashboard/stats', [DashboardController::class, 'stats']);

        Route::apiResource('members', MemberController::class);
        Route::apiResource('groups', GroupController::class);
        Route::post('/members/{member}/groups/{group}', [MemberController::class, 'assignGroup']);

        Route::get('/attendance/services', [AttendanceController::class, 'serviceIndex']);
        Route::post('/attendance/services', [AttendanceController::class, 'storeServiceAttendance']);
        Route::get('/attendance/events', [AttendanceController::class, 'eventIndex']);
        Route::post('/attendance/events', [AttendanceController::class, 'storeEventAttendance']);

        Route::apiResource('events', EventController::class);
        Route::post('/events/{event}/tasks', [EventController::class, 'storeTask']);
        Route::post('/events/{event}/budget-items', [EventController::class, 'storeBudgetItem']);
        Route::get('/events/{event}/report', [EventController::class, 'report']);

        Route::apiResource('finance/entries', FinanceController::class);
        Route::get('/finance/reports/daily', [FinanceController::class, 'daily']);
        Route::get('/finance/reports/monthly', [FinanceController::class, 'monthly']);
        Route::get('/finance/reports/yearly', [FinanceController::class, 'yearly']);
        Route::get('/finance/reports/export', [FinanceController::class, 'export']);

        Route::apiResource('procurement/requests', ProcurementController::class);
        Route::post('/procurement/requests/{request}/submit', [ProcurementController::class, 'submit']);
        Route::post('/procurement/requests/{request}/approve', [ProcurementController::class, 'approve']);
        Route::post('/procurement/requests/{request}/reject', [ProcurementController::class, 'reject']);
        Route::post('/procurement/requests/{request}/purchase-order', [ProcurementController::class, 'createPurchaseOrder']);

        Route::apiResource('assets', AssetController::class);
        Route::post('/assets/{asset}/assign', [AssetController::class, 'assign']);
        Route::post('/assets/{asset}/maintenance', [AssetController::class, 'maintenance']);

        Route::post('/communication/sms/broadcast', [CommunicationController::class, 'broadcast']);
        Route::post('/communication/sms/reminder', [CommunicationController::class, 'eventReminder']);

        Route::get('/reports/attendance', [ReportController::class, 'attendance']);
        Route::get('/reports/events', [ReportController::class, 'events']);
        Route::get('/reports/procurement', [ReportController::class, 'procurement']);
        Route::get('/reports/assets', [ReportController::class, 'assets']);
        Route::get('/reports/financial', [ReportController::class, 'financial']);
    });
});
