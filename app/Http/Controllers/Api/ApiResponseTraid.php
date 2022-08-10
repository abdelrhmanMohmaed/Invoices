<?php

namespace App\Http\Controllers\Api;


trait ApiResponseTraid
{
    public function ApiResponse($data = null, $status = Null, $message = null)
    {
        $array = [
            'data' => $data,
            'message' => $message,
            'status' => $status,
        ];
        return response($array, $status);
    }
}
