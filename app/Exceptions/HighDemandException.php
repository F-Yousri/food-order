<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;

class HighDemandException extends Exception
{
    public function render(Request $request)
    {
        return response()->json([
            'message' => "We're experiencing exceptionally high demand. Please try again later.",
        ], 503, ['Retry-After' => 10]);
    }
}
