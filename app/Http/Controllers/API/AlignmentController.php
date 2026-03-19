<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Alignment;
use Illuminate\Http\Request;

class AlignmentController extends Controller
{
    //
    public function index(Request $request){
        $alignments=Alignment::orderBy('id','asc')->get();
        \ResponseData($alignments);
    }
}
