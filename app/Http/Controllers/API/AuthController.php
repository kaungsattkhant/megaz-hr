<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Actions\Auth\APILoginAction;

use App\Http\Controllers\Controller;
use App\Http\Requests\StaffLoginRequest;

use App\Models\Staff;

class AuthController extends Controller
{
    //
    public function login(StaffLoginRequest $request)
    {
        $loginResponse = (new APILoginAction("phone_number", $request->phone_number, $request->password, "App\Models\Staff"))->run("staff_token");

        if($loginResponse["code"] != 200){
            ResponseMessage($loginResponse["message"], 401);
        }

        else{
            $staff = Staff::find($loginResponse["user"]["id"]);
            ResponseData($loginResponse);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        ResponseMessage("Logout success");
    }
}
