<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'Authenticate user and issue Sanctum token.',
            'hint' => 'Validate phone/email + password, then return token and role permissions.'
        ]);
    }
}
