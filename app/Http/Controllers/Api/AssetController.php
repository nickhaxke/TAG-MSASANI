<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['message' => 'List assets with condition and location filters.']);
    }

    public function store(Request $request): JsonResponse
    {
        return response()->json(['message' => 'Register a new asset.'], 201);
    }

    public function show(int $asset): JsonResponse
    {
        return response()->json(['message' => "Get asset {$asset} with assignment and maintenance history."]);
    }

    public function update(Request $request, int $asset): JsonResponse
    {
        return response()->json(['message' => "Update asset {$asset}."]);
    }

    public function destroy(int $asset): JsonResponse
    {
        return response()->json(['message' => "Retire or deactivate asset {$asset}."]);
    }

    public function assign(Request $request, int $asset): JsonResponse
    {
        return response()->json(['message' => "Assign asset {$asset} to user, event, or location."]);
    }

    public function maintenance(Request $request, int $asset): JsonResponse
    {
        return response()->json(['message' => "Record maintenance activity for asset {$asset}."], 201);
    }
}
