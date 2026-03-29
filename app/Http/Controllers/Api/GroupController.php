<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['message' => 'List groups and member counts.']);
    }

    public function store(Request $request): JsonResponse
    {
        return response()->json(['message' => 'Create a ministry group.'], 201);
    }

    public function show(int $group): JsonResponse
    {
        return response()->json(['message' => "Get group {$group} details and active members."]);
    }

    public function update(Request $request, int $group): JsonResponse
    {
        return response()->json(['message' => "Update group {$group}."]);
    }

    public function destroy(int $group): JsonResponse
    {
        return response()->json(['message' => "Archive group {$group}."]);
    }
}
