<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class LogoutController extends Controller
{
    public function __invoke(Request $request)
    {
        try {

            JWTAuth::invalidate(JWTAuth::getToken());
        } catch (TokenInvalidException $e) {
            return response()->json(['message' => 'Already logged out'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to logout, please try again'], 419);
        }

        return response()->json(['message' => 'Successfully logged out']);
    }
}
