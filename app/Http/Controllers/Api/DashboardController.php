<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function stats(): JsonResponse
    {
        return response()->json([
            'members_count' => 0,
            'monthly_income' => 0,
            'upcoming_events' => 0,
            'monthly_expenses' => 0,
            'notes' => 'Replace with aggregated SQL queries on members, finance_entries, and events.'
        ]);
    }
}
