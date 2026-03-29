<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommunicationController extends Controller
{
    public function broadcast(Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'Queue SMS broadcast by member IDs or group IDs.',
            'hint' => 'Integrate with Beem, Africa\'s Talking, or local provider API.'
        ], 201);
    }

    public function eventReminder(Request $request): JsonResponse
    {
        return response()->json(['message' => 'Queue event reminder SMS to registered attendees.'], 201);
    }
}
