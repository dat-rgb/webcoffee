<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use PayOS\PayOS;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected PayOS $payOS;

    public function __construct()
    {
        $clientId = env("PAYOS_CLIENT_ID");
        $apiKey = env("PAYOS_API_KEY");
        $checksumKey = env("PAYOS_CHECKSUM_KEY");

        if (!$clientId || !$apiKey || !$checksumKey) {
            throw new \Exception("Thiếu cấu hình PAYOS trong .env");
        }

        $this->payOS = new PayOS($clientId, $apiKey, $checksumKey);
    }

    protected function handleException(\Throwable $th)
    {
        return response()->json([
            "error" => $th->getCode(),
            "message" => $th->getMessage(),
            "data" => null
        ], 500); 
    }
}
