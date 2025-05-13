<?php

namespace App;

use Illuminate\Http\Request;
use App\Events\ResourceConsumed;

trait HandleResponse
{
    public function dispatchAndResponse(Request $request, string $resource, int $status, array $message)
    {
        ResourceConsumed::dispatch($request->user()->id, $resource, $status, json_encode($message));
        return response()->json($message, $status);
    }
}
