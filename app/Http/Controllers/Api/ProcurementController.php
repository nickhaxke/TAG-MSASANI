<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProcurementController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['message' => 'List purchase requests and approval states.']);
    }

    public function store(Request $request): JsonResponse
    {
        return response()->json(['message' => 'Create purchase request.'], 201);
    }

    public function show(int $request): JsonResponse
    {
        return response()->json(['message' => "Get purchase request {$request} with approvals."]);
    }

    public function update(Request $requestData, int $request): JsonResponse
    {
        return response()->json(['message' => "Update purchase request {$request}."]);
    }

    public function destroy(int $request): JsonResponse
    {
        return response()->json(['message' => "Archive purchase request {$request}."]);
    }

    public function submit(int $request): JsonResponse
    {
        return response()->json(['message' => "Submit purchase request {$request} for approval."]);
    }

    public function approve(Request $requestData, int $request): JsonResponse
    {
        return response()->json(['message' => "Approve purchase request {$request}."]);
    }

    public function reject(Request $requestData, int $request): JsonResponse
    {
        return response()->json(['message' => "Reject purchase request {$request}."]);
    }

    public function createPurchaseOrder(Request $requestData, int $request): JsonResponse
    {
        return response()->json(['message' => "Generate purchase order for request {$request}."], 201);
    }
}
