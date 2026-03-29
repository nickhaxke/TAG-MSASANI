<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function attendance(Request $request): JsonResponse
    {
        return response()->json(['message' => 'Attendance report by service/event/group/date range.']);
    }

    public function events(Request $request): JsonResponse
    {
        return response()->json(['message' => 'Event performance report with budget vs actual.']);
    }

    public function procurement(Request $request): JsonResponse
    {
        return response()->json(['message' => 'Procurement report by status, supplier, and cost.']);
    }

    public function assets(Request $request): JsonResponse
    {
        return response()->json(['message' => 'Asset condition, assignment, and maintenance report.']);
    }

    public function financial(Request $request): JsonResponse
    {
        return response()->json(['message' => 'Financial report by period and category.']);
    }
}
