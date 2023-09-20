<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;

class baseController extends APIController
{
    public function responseObject($data = null, $message = 'success'){
        return response()->json([
            'status'    => 200,
            'message'   => $message,
            'data'      => $data,
        ], 200);
    }

    public function responseListObject($data = [], $message = 'success', $page = 0, $totalPage = 0){
        return response()->json([
            'status'    => 200,
            'message'   => $message,
            'data'      => $data,
            'page'      => $page,
            'total_page' => $totalPage
        ], 200);
    }

    public function responseStatus($message = 'success'){
        return response()->json([
            'status'    => 200,
            'message'   => $message,
        ], 200);
    }

    public function responseAuthStatus($token){
        return response()->json([
            'status' => 201,
            'message' => 'success',
        ], 201);
    }


    // ================
    // RESPONSE ERROR
    // ================

    public function responseServerError($message = 'Internal Server Error'){
        return response()->json([
            'status'    => 500,
            'message'   => $message,
        ], 500);
    }

    public function responseNotFound($message = 'Not Found'){
        return response()->json([
            'status'    => 404,
            'message'   => $message,
        ], 404);
    }

    public function responseUnauthorized($message = 'Unauthorized'){
        return response()->json([
            'status'    => 401,
            'message'   => $message,
        ], 401);
    }

    public function responseBadRequest($message = 'Bad Request'){
        return response()->json([
            'status'    => 400,
            'message'   => $message,
        ], 400);
    }
}
