<?php

namespace App\Http\Controllers;

use App\Models\CrmConnection;
use App\Models\CrmWebhookLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CrmWebhookController extends Controller
{
    public function handle(Request $request): JsonResponse
    {
        $payload = $request->json()->all();
        $event = $request->header('x-crm-event') ?? ($payload['event'] ?? 'unknown');

        $connection = CrmConnection::where('is_active', true)->first();

        if (! $connection) {
            return response()->json(['received' => false], 404);
        }

        $log = CrmWebhookLog::create([
            'connection_id' => $connection->id,
            'event' => $event,
            'payload' => $payload,
            'status' => 'received',
        ]);

        // Known events are acknowledged here; deeper state mapping (e.g. deal stage
        // -> order status) is deferred until a live Zoho pipeline is connected.
        $log->update(['status' => 'processed']);

        return response()->json(['received' => true]);
    }
}
