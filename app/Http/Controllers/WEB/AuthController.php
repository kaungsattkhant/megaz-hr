<?php

namespace App\Http\Controllers\WEB;

use Illuminate\Http\Request;
use App\Models\StaffFcmToken;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
            $this->storeFcmToken($request->fcm_token);
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
            return redirect()->back();
        }
    }
    public function storeFcmToken($token){
        if($token){
            $staff = StaffFcmToken::firstOrCreate(
                ['fcm_token' =>$token,
                'user_id'=>UserData()->id,
            ],
                ['fcm_token' =>$token,
                 'user_id'=>UserData()->id]
            );
            return $staff;
        }
    }
}
