<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function JsonReturnTrue($data = [])
    {
        return response()->json([
            'status'  => true,
            'code'    => 200,
            'message' => config('errorcode.code')[200],
            'data'    => $data,
        ]);
    }

    public function JsonReturnFalse($code,$message, $data = [])
    {
        return response()->json([
            'status'  => false,
            'code'    => $code,
            'message' => $message,
            'data'    => $data,
        ]);
    }
}
