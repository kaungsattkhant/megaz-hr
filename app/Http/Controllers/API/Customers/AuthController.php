<?php

namespace App\Http\Controllers\API\Customers;

use Exception;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Actions\Auth\APILoginAction;

use App\Http\Controllers\Controller;

use App\Models\Customer;

class AuthController extends Controller
{
    //
    public function initialRegister(Request $request)
    {
        if(!$request->phone_number){
            ResponseMessage('Phone number must be present');
        }
        if(!$request->name){
            ResponseMessage('Name must be present');
        }
        if(!$request->gender_id){
            ResponseMessage('Gender must be selected');
        }
        $data = $request->all();
        //$data['otp'] = rand(000000,999999);
        $data['otp'] = '000000';
        $data['password'] = 'default_password';

        try{
            DB::beginTransaction();
            $customer = Customer::create($data);
            DB::commit();
            ResponseMessage('OTP code sent, please check your SMS');
        }
        catch(Exception $e){
            DB::rollBack();
            ResponseMessage($e->getMessage(), 500);
        }
    }

    public function register(Request $request)
    {
        $customer = Customer::where('phone_number', $request->phone_number)->first();
        if(!$customer){
            ResponseMessage('No customer found with given phone number', 400);
        }
        if(!$request->password){
            ResponseMessage('Password must be present');
        }

        if($customer->checkOtp($request->otp)){
            $data = $request->all();
            $data['is_verified'] = 1;
            try{
                DB::beginTransaction();
                $customer->update($data);
                $customer->save();
                DB::commit();
                ResponseMessage('Customer created and verified successfully');
            }
            catch(Exception $e){
                DB::rollBack();
                ResponseMessage($e->getMessage(), 500);
            }
        }
        else{
            ResponseMessage('OTP code not match, please try again');
        }
    }

    public function login(Request $request)
    {
        $loginResponse = (new APILoginAction("phone_number", $request->phone_number, $request->password, "App\Models\Customer"))->run("customer_token");

        if($loginResponse["code"] != 200){
            ResponseMessage($loginResponse["message"], 401);
        }
        else{
            $customer = Customer::find($loginResponse["user"]["id"]);
            $loginResponse["customer"] = $customer;
            ResponseData($loginResponse);
        }
    }
}
