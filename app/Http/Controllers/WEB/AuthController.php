<?php

namespace App\Http\Controllers\WEB;

use Illuminate\Http\Request;

use App\Models\StaffFcmToken;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        $remember = false;
        if (isset($request->remember)) {
            $remember = true;
        }
        if (Auth::attempt(['phone_number' => $request->phone_number, 'password' => $request->password], $remember)) {
            // $firstFeaturePermission = UserData()->features->first(); 
            // if ($firstFeaturePermission) {
            //     $routeName = config('feature_route.' . $firstFeaturePermission->module);
            //     if ($routeName) {
            //         return redirect()->route($routeName);
            //     }
            //     return redirect()->back();
            // }
            // return redirect()->back();
            $features = UserData()->features;
            $i = 0;
            while ($i < $features->count()) {
                $feature = $features[$i];
                $routeName = config('feature_route.' . $feature->module);
                if ($routeName && Route::has($routeName)) {
                    return redirect()->route($routeName);
                }

                $i++;
            }
            return redirect()->back();


        } else {
            return redirect()->back();
        }
    }
    public function storeFcmToken($token)
    {
        if ($token) {
            $staff = StaffFcmToken::firstOrCreate(
                [
                    'fcm_token' => $token,
                    'user_id' => UserData()->id,
                ],
                [
                    'fcm_token' => $token,
                    'user_id' => UserData()->id
                ]
            );
            return $staff;
        }
    }
}
