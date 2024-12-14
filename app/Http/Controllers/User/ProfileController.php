<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;

class ProfileController extends Controller
{
    public function __invoke()
    {
        return response()->json(UserResource::make(auth()->user()));
    }
}
