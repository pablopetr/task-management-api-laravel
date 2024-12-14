<?php

namespace App\Http\Controllers;

class TestController extends Controller
{
    public function __invoke()
    {
        return response()->json(['message' => 'Hello World!']);
    }
}
