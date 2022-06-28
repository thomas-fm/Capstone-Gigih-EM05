<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

use Illuminate\Http\Request;

class Helper
{
    public static function SuccessResponse(bool $success, string $message, $data)
    {
        return response()->json([
            "success" => true,
            "message" => $message,
            "data" => $data
        ]);
    }


    public static function ErrorResponse(string $message, int $code)
    {
        return response()->json([
            "success" => false,
            "message" => $message,
        ], $code);
    }
}
