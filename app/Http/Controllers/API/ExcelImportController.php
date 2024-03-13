<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Imports\AccountsImport;
use Maatwebsite\Excel\Facades\Excel;

class ExcelImportController extends Controller
{
    //
    public function importAccount(Request $request){
        $this->import(new AccountsImport(),$request->sheet);
        ResponseMessage('Import Successfully',200);
    }
    protected function import($import,$file){
        Excel::import($import, $file);
    }
}
