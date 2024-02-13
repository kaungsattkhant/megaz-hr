<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Models\Staff;

class ProfileAPIController extends Controller
{
    //
    public function getProfile(Request $request)
    {
        $staff = Staff::with(['department','gender', 'roles'])->find($request->user()->id);

        ResponseData($staff);
    }
}
