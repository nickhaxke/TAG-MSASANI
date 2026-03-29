<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['message' => 'List members with filters (status, group, region).']);
    }

    public function store(Request $request): JsonResponse
    {
        return response()->json(['message' => 'Create member profile with validation and audit log.'], 201);
    }

    public function show(int $member): JsonResponse
    {
        return response()->json(['message' => "Get full member profile {$member}."]);
    }

    public function update(Request $request, int $member): JsonResponse
    {
        return response()->json(['message' => "Update member {$member} and append audit trail."]);
    }

    public function destroy(int $member): JsonResponse
    {
        return response()->json(['message' => "Soft-delete or deactivate member {$member}."]);
    }

    public function assignGroup(int $member, int $group): JsonResponse
    {
        return response()->json(['message' => "Assign member {$member} to group {$group}."]);
    }
}
