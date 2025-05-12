<?php

namespace App;

use App\Events\ResourceConsumed;

trait HandleResponse
{
    public function dispatchAndResponse(int $id, string $resource, int $status, array $message)
    {
        ResourceConsumed::dispatch($id, $resource, $status, json_encode($message));
        return response()->json($message, $status);
    }
}
