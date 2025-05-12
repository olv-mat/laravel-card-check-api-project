<?php

namespace App\Http\Controllers;

use App\HandleResponse;
use App\Events\ResourceConsumed;
use App\Http\Controllers\Controller;
use App\Http\Requests\BINRequest;
use Illuminate\Support\Facades\Http;

class BINController extends Controller
{
    use HandleResponse;

    public function check(BINRequest $request)
    {
        $userId = $request->user()->id;
        $resource = "bin/check";

        $key = env("API_KEY");
        $endpoint = env("API_ENDPOINT");

        if (is_null($key) || is_null($endpoint)) {
            $message = ["message" => "Key or endpoint is not configured."];
            return $this->dispatchAndResponse($userId, $resource, 500, $message);
        }
        
        $bin = $request->bin;
        $headers = ["apikey" => $key];

        $response = Http::withHeaders($headers)->get("$endpoint/$bin");

        if ($response->status() == 404) {
            $message = ["message" => "The BIN was not found or does not exist."];
            return $this->dispatchAndResponse($userId, $resource, 200, $message);
        }

        $message = ["scheme" => $response->json(["scheme"])];

        return $this->dispatchAndResponse($userId, $resource, 200, $message);

    }
}
