<?php

namespace App\Http\Middleware;

use App\Enum\RoleEnum;
use Closure;
use Illuminate\Http\Request;

class CheckIsAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()->role !== RoleEnum::ADMIN->value) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
