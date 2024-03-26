<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    //
    public function toggleIsActive(Request $request){
        $type=Model($request->type);
        $model=$type::find($request->id);
        if($model){
            $model->update(['is_active' => !$model->is_active]);
            ResponseMessage('Update successfully',200);
        }
        ResponseMessage('Update fail',422);
    }
}
