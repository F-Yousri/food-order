<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;

class InsuffecientIngredientsException extends Exception
{
    public function render(Request $request)
    {
        return response()->json([
            'message' => 'The ingredients available are insuffecient to complete the order.',
        ], 422);
    }
}
