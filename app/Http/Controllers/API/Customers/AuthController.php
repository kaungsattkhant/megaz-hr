<?php

namespace App\Http\Controllers\API\Customers;

use Exception;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Actions\Auth\APILoginAction;

use App\Http\Controllers\Controller;

use App\Models\Customer;
use App\Models\CustomerAddress;

class AuthController extends Controller
{
    //
    public function initialRegister(Request $request)
    {
        if (!$request->phone_number) {
            ResponseMessage('Phone number must be present');
        }
        if (!$request->name) {
            ResponseMessage('Name must be present');
        }
        if (!$request->gender_id) {
            ResponseMessage('Gender must be selected');
        }
        $data = $request->all();
        //$data['otp'] = rand(000000,999999);
        $data['otp'] = '000000';
        $data['password'] = 'default_password';

        try {
            DB::beginTransaction();
            $customer = Customer::create($data);
            DB::commit();
            ResponseMessage('OTP code sent, please check your SMS');
        } catch (Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 500);
        }
    }

    public function register(Request $request)
    {
        $customer = Customer::where('phone_number', $request->phone_number)->first();
        if (!$customer) {
            ResponseMessage('No customer found with given phone number', 400);
        }
        if (!$request->password) {
            ResponseMessage('Password must be present');
        }

        if ($customer->checkOtp($request->otp)) {
            $data = $request->all();
            $data['is_verified'] = 1;
            try {
                DB::beginTransaction();
                $customer->update($data);
                if (isset($request->address)) {
                    CustomerAddress::create([
                        'customer_id' => $customer->id,
                        'address' => 'addresss',
                        'is_default' => 1,
                        'township_id' => $data['township_id'] ?? null
                    ]);
                }
                $customer->save();

                $loginResponse = (new APILoginAction("phone_number", $request->phone_number, $request->password, "App\Models\Customer"))
                    ->run("customer_token");
                $loginResponse['user']['login_type'] = 'customer';
                DB::commit();

                ResponseData($loginResponse, 201, true, 'Successfully registered and verified');
            } catch (Exception $e) {
                DB::rollBack();
                ResponseMessage($e->getMessage(), 500);
            }
        } else {
            ResponseMessage('OTP code not match, please try again');
        }
    }

    public function login(Request $request)
    {
        $loginResponse = (new APILoginAction("phone_number", $request->phone_number, $request->password, "App\Models\Customer"))->run("customer_token");

        if ($loginResponse["code"] != 200) {
            ResponseMessage($loginResponse["message"], 401);
        } else {
            $customer = Customer::find($loginResponse["user"]["id"]);
            $loginResponse["customer"] = $customer;
            ResponseData($loginResponse);
        }
    }
}
