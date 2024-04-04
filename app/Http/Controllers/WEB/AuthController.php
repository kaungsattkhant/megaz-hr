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
            if(checkDepartmentPermission(['HR'])){
                return redirect()->route('staff');
            }
            if(checkDepartmentPermission(['Finance'])){
                return redirect()->route('financial_transactions');
            }
            if(checkDepartmentPermission(['Management'])){
                return redirect()->route('purchase_orders');
            }
            if(checkDepartmentPermission(['Catering'])){
                return redirect()->route('pos.index');
            }
            return redirect()->route('staff');
        }else{
            dd('ef');
            return redirect()->back();
        }
    }
}
