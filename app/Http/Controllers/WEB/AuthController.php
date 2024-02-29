<?php

namespace App\Http\Controllers\WEB;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        $remember = false;
        if (isset($request->remember)) {
            $remember = true;
        }
        if(Auth::attempt(['phone_number'=>$request->phone_number,'password' => $request->password], $remember)){
            return redirect()->route('staff');
        }else{
            return redirect()->back();
        }
    }
}
