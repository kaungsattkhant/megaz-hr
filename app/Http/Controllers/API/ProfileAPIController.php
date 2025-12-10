<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Http\Requests\Profile\ChangePasswordRequest;

use App\Models\Staff;
use Illuminate\Support\Facades\Hash;

class ProfileAPIController extends Controller
{
    //
    public function getProfile(Request $request)
    {
        $staff = Staff::with(['department','gender', 'roles'])->find($request->user()->id);
        ResponseData($staff);
    }

    public function updatePassword(ChangePasswordRequest $request)
    {
        $staff = Staff::find($request->user()->id);
        if(Hash::check($request->old_password, $staff->getAuthPassword())){
            $staff->password = $request->password;
            $staff->save();
            ResponseMessage('Password changed successfully');
        }
        else{
            ResponseMessage('Old password not match', 422);
        }
    }
}
