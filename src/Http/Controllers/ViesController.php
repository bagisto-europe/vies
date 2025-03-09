<?php

namespace Bagisto\Vies\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ViesController extends Controller
{
    /**
     * Check if VIES API service is available.
     */
    public function checkViesStatus()
    {
        try {
            $response = Http::get('https://ec.europa.eu/taxation_customs/vies/rest-api/check-status');

            if ($response->failed()) {
                Log::error("VIES API Error: {$response->body()}");

                return response()->json(['error' => 'VIES API is unavailable'], 500);
            }

            $data = $response->json();
            $available = $data['vow']['available'] ?? false;

            return dd($data);

            return response()->json(['available' => $available]);
        } catch (\Exception $e) {
            Log::error('VIES API Exception: '.$e->getMessage());

            return response()->json(['error' => 'Unexpected error occurred'], 500);
        }
    }
}
