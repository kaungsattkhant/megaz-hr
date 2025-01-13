<?php

namespace App\Http\Controllers\API\Customers;

use Exception;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Actions\Auth\APILoginAction;

use App\Http\Controllers\Controller;

use App\Http\Requests\Customer\CustomerRequest;

use App\Models\Customer;
use App\Models\CustomerAddress;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    public function initialRegister(CustomerRequest $request)
    {
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
            Customer::firstOrCreate(
                ['phone_number' => $request->phone_number, 'is_verified' => 0],
                $data // Default values to create a new user
            );
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
                        'name' => $data['address_name'],
                        'customer_id' => $customer->id,
                        'address' => $data['address'],
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

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        ResponseMessage("Logout success",200);
    }

    public function forgotPassword(Request $request)
    {
        DB::beginTransaction();
        try {
            $data['otp'] = '000000';
            $customer = Customer::where('phone_number', $request->phone_number)->first();
            if (!$customer) {
                ResponseMessage('No customer found with given phone number', 400);
            }
            $customer->update($data);
            DB::commit();
            ResponseMessage('OTP code sent, please check your SMS',200);
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    public function changeForgetPassword(Request $request)
    {
        DB::beginTransaction();
        try {

            $customer = Customer::where('phone_number', $request->phone_number)->first();
            if (!$customer) {
                ResponseMessage('No customer found with given phone number', 400);
            }

            if ($customer->otp == $request->otp) {
                if ($request->password == $request->confirm_password) {
                    $customer->password = $request->password;
                    $customer->save();
                    DB::commit();
                    $this->login($request);
                } else {
                    ResponseMessage('Password and confirm password not match', 400);
                }
                ResponseMessage('Password changed successfully',200);
            } else {
                ResponseMessage('OTP code not match, please try again', 400);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }


    public function changePassword(Request $request)
    {
        DB::beginTransaction();
        try {

            $customer = Customer::find(UserData()->id);
            if (!$customer) {
                ResponseMessage('No customer found with given phone number', 400);
            }
            if (Hash::check($request->current_password, $customer->password)) {
                if ($request->password == $request->confirm_password) {
                    $customer->password = $request->password;
                    $customer->save();
                    DB::commit();
                    ResponseMessage('Password changed successfully');
                } else {
                    ResponseMessage('Password and confirm password not match', 402);
                }
            } else {
                ResponseMessage('Current password not match', 402);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

}
