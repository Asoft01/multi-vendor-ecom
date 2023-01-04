<?php

namespace App\Http\Helpers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

class ApiResponse
{
    static function send($error, $messages, $data, $status): Response|Application|ResponseFactory
    {
        $response = [
            'error' => $error,
            'message' => is_array($messages) ? count($messages) > 0 ? $messages[0] : "" : $messages,
        ];
        if ($data !== null) {
            $response['data'] = $data;
        }
        return response($response, $status);
    }

    static function success($messages, $data): Response|Application|ResponseFactory
    {
        $response = [
            'error' => false,
            'message' => is_array($messages) ? count($messages) > 0 ? $messages[0] : "" : $messages,
        ];
        if ($data !== null) {
            $response['data'] = $data;
        }
        return response($response, 200);
    }


    static function error($messages, $data): Response|Application|ResponseFactory
    {
        $response = [
            'error' => true,
            'message' => is_array($messages) ? count($messages) > 0 ? $messages[0] : "" : $messages,
        ];
        if ($data !== null) {
            $response['data'] = $data;
        }
        return response($response, 422);
    }

    static function sendPaginated($error, $messages, $data, $status): Response|Application|ResponseFactory
    {
        $response = collect([
            'error' => $error,
            'message' => is_array($messages) ? count($messages) > 0 ? $messages[0] : "" : $messages,
        ]);
        return response($response->merge($data), $status);
    }

    // if ($promotion->startDate > $request->expiryDate) {
    //     // return ApiResponse::send(true, "start date can not be greater than expiry date", null, 200);
    //     return ApiResponse::error("start date can not be greater than expiry date", null);
    // }
}
