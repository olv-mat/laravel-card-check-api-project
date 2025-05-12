<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\BINRequest;
use Illuminate\Support\Facades\Http;

class BINController extends Controller
{
    public function check(BINRequest $request)
    {
        $key = env("API_KEY");
        $endpoint = env("API_ENDPOINT");
        
        if (is_null($key) || is_null($endpoint)) {
            return response()->json(["message" => "Key or endpoint is not configured."], 500);
        }
        
        $bin = $request->bin;

        $response = Http::withHeaders([
            "apikey" => $key
        ])->get("$endpoint/$bin");

        if ($response->status() == 404) {
            return response()->json(["message" => "The BIN was not found or does not exist."], 200);
        }

        return response()->json(["scheme" => $response->json(["scheme"])], 200);

    }
}
