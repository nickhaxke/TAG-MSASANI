<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function serviceIndex(Request $request): JsonResponse
    {
        return response()->json(['message' => 'Service attendance listing by date/service/member.']);
    }

    public function storeServiceAttendance(Request $request): JsonResponse
    {
        return response()->json(['message' => 'Record service attendance in bulk.'], 201);
    }

    public function eventIndex(Request $request): JsonResponse
    {
        return response()->json(['message' => 'Event attendance listing by event and status.']);
    }

    public function storeEventAttendance(Request $request): JsonResponse
    {
        return response()->json(['message' => 'Record event attendee check-in data.'], 201);
    }
}
