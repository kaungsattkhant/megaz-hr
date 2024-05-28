<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class DepartmentPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,$permission): Response
    {
        $staff = UserData();
        if(checkFeaturePermission($permission)){
            
            return $next($request);
        }
        // foreach($departments as $department){
        //     if($department == "Catering"){
        //         return redirect()->route('pos.login');
        //     }
        // }

        return redirect()->route('login');
    }
}
