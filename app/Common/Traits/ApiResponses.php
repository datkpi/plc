<?php

namespace App\Common\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponses
{
    protected function success($data = [], $message = "Thành công", $status = 200)
    {
        return response([
            'success' => true,
            'data' => $data,
            'message' => $message,
        ], $status);
    }

    protected function error($message = "Có lỗi xảy ra", $status = 200)
    {
        return response([
            'success' => false,
            'message' => $message,
        ], $status);
    }
    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    // public function error($error = "", $errorMessages = [], $code = 404)
    // {
    //     $response = [
    //         'success' => false,
    //         'message' => $error,
    //     ];

    //     if (!empty($errorMessages)) {
    //         $response['data'] = $errorMessages;
    //     }

    //     return response()->json($response, $code);
    // }



}