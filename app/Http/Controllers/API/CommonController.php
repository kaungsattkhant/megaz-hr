<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    //
    public function toggleIsActive(Request $request){
        $type=Model($request->type);
        $column='is_active';
        if($request->type=='entity'){
            $column='is_available';
        }
        $model=$type::find($request->id);
        if($model){
            $model->update([$column => !$model->$column]);
            ResponseMessage('Update successfully',200);
        }
        ResponseMessage('Update fail',422);
    }
}
