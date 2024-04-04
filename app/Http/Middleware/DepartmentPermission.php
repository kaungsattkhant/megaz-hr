<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DepartmentPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,...$departments): Response
    {
        if(checkDepartmentPermission($departments)){
            return $next($request);
        }
        foreach($departments as $department){
            if($department == "Catering"){
                return redirect()->route('pos.login');
            }
        }

        return redirect()->route('login');
    }
}
