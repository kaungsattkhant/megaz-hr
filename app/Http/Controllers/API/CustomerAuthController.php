<?php

namespace App\Http\Controllers\API;

use App\Actions\Auth\APILoginAction;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerAuthController extends Controller
{
    public function customerLogin(Request $request)
    {
        $loginResponse = (new APILoginAction("phone_number", $request->phone_number, $request->password, "App\Models\Customer"))->run("customer_token");
        if($loginResponse["code"] != 200){
            ResponseMessage($loginResponse["message"], 401);
        }
        else{
            $customer = Customer::find($loginResponse["user"]["id"]);
            $loginResponse["user"] = $customer;
            ResponseData($loginResponse);
        }
    }
}
