<?php

namespace App\Services;

class ResponseService
{
    public static function sendResponse($status, $message, $data = null, $error = null)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
            'error' => $error,
        ], $status);
    }

    public static function success($message, $data = null)
    {
        return self::sendResponse(200, $message, $data);
    }

    public static function error($message, $error = null, $status = 400)
    {
        return self::sendResponse($status, $message, null, $error);
    }
}


?>