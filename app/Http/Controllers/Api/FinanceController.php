<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['message' => 'List finance entries with category and source filters.']);
    }

    public function store(Request $request): JsonResponse
    {
        return response()->json(['message' => 'Record income/expense and link source modules when needed.'], 201);
    }

    public function show(int $entry): JsonResponse
    {
        return response()->json(['message' => "Get finance entry {$entry}."]);
    }

    public function update(Request $request, int $entry): JsonResponse
    {
        return response()->json(['message' => "Update finance entry {$entry}."]);
    }

    public function destroy(int $entry): JsonResponse
    {
        return response()->json(['message' => "Reverse or archive finance entry {$entry}."]);
    }

    public function daily(Request $request): JsonResponse
    {
        return response()->json(['message' => 'Daily financial summary report.']);
    }

    public function monthly(Request $request): JsonResponse
    {
        return response()->json(['message' => 'Monthly financial summary report.']);
    }

    public function yearly(Request $request): JsonResponse
    {
        return response()->json(['message' => 'Yearly financial summary report.']);
    }

    public function export(Request $request): JsonResponse
    {
        return response()->json(['message' => 'Export financial reports as PDF/Excel.']);
    }
}
