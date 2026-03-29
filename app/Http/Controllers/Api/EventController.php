<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['message' => 'List events with budget and attendance summaries.']);
    }

    public function store(Request $request): JsonResponse
    {
        return response()->json(['message' => 'Create event and optional initial budget lines.'], 201);
    }

    public function show(int $event): JsonResponse
    {
        return response()->json(['message' => "Get event {$event} with tasks, budget and attendance."]);
    }

    public function update(Request $request, int $event): JsonResponse
    {
        return response()->json(['message' => "Update event {$event}."]);
    }

    public function destroy(int $event): JsonResponse
    {
        return response()->json(['message' => "Cancel event {$event}."]);
    }

    public function storeTask(Request $request, int $event): JsonResponse
    {
        return response()->json(['message' => "Assign task for event {$event}."], 201);
    }

    public function storeBudgetItem(Request $request, int $event): JsonResponse
    {
        return response()->json(['message' => "Add or update budget item for event {$event}."], 201);
    }

    public function report(int $event): JsonResponse
    {
        return response()->json(['message' => "Generate event report for {$event} (income, expenses, attendance)."]);
    }
}
