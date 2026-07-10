<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\JsonResponse;

class TrackController extends Controller
{
    public function show(string $code): JsonResponse
    {
        $order = Order::with(['trackingEvents' => fn ($q) => $q->orderByDesc('created_at')])
            ->where('tracking_code', $code)
            ->first();

        if (! $order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        return response()->json($order);
    }
}
