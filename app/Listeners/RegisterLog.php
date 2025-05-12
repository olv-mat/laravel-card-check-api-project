<?php

namespace App\Listeners;

use App\Models\Log;
use App\Events\ResourceConsumed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RegisterLog
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ResourceConsumed $event): void
    {
        $logData = [
            "user_id" => $event->userId,
            "resource" => $event->resource,
            "status" => $event->status,
            "message" => $event->message,
        ];
        Log::create($logData);
    }
}
