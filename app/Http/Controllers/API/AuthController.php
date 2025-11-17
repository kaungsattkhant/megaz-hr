<?php

namespace App\Http\Controllers\API;

use App\Models\Staff;

use Illuminate\Http\Request;

use App\Models\StaffFcmToken;

use App\Models\PersonFcmToken;

use App\Actions\Auth\APILoginAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StaffLoginRequest;

class AuthController extends Controller
{
    //
    public function login(StaffLoginRequest $request)
    {
        $loginResponse = (new APILoginAction("phone_number", $request->phone_number, $request->password, "App\Models\Staff"))->run("staff_token");

        if ($loginResponse["code"] != 200) {
            ResponseMessage($loginResponse["message"], $loginResponse["code"]);
        } else {
            $staff = Staff::with(["gender", "department", "roles"])->find($loginResponse["user"]["id"]);
            $this->storeFcmToken($request->fcm_token, $loginResponse['user']['id']);

            // dd($staff->features->pluck('slug')->toArray());
            $loginResponse["user"] = $staff;
            ResponseData($loginResponse);
        }
    }
    public function storeFcmToken($token, $customerId)
    {
        // Log::info('token ' , (array)$token);
        if ($token && $token != "null") {
            
            $personToken = PersonFcmToken::firstOrCreate(
                [
                    'fcm_token' => $token,
                    'personable_id' => $customerId,
                    'personable_type' => 'staff',

                ],
                [
                    'fcm_token' => $token,
                    'personable_id' => $customerId,
                    'personable_type' => 'staff'
                ]
            );
            return $personToken;
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        ResponseMessage("Logout success");
    }


}
