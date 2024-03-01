<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
       
        if($request->expectsJson()){
            ResponseMessage('You are not authenticated', 401);
            // return route('login_form');
        }
        else{
            return route('login_form');
        }
    }

    
}
