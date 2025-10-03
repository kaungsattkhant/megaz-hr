<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Imports\RawItemImport;
use App\Http\Controllers\Controller;
use App\Imports\ReadyToSaleItemImport;
use Maatwebsite\Excel\HeadingRowImport;

class MenuItemImportController extends Controller
{
    public function readyToSaleMenuItemImport(Request $request)
    {
        $file = $request->file('ready_to_sale_menu_item_import');
        $headings = (new HeadingRowImport)->toArray($file);
        $expectedHeadings = [
            'date',
            'inventory_id',
            'menu_code',
            'quantity',
        ];
        $actualHeadings = $headings[0][0];
        foreach ($expectedHeadings as $heading) {
            if (!in_array($heading, $actualHeadings)) {
                return ResponseData($data = null, $status_code = 422, false, $extra_message = 'Missing Heading: ' . $heading);
            }
        }
        $import = new ReadyToSaleItemImport();
        $import->import($file);
        ResponseMessage('Ready To Sale Items Import Successfully', 200);
    }

    public function rawItemImport(Request $request)
    {
        $file = $request->file('raw-item-import');
        $headings = (new HeadingRowImport)->toArray($file);
        $expectedHeadings = [
            'date',
            'inventory_id',
            'item_code',
            'base_uom_code',
            'base_uom_quantity',
            'uom_code',
            'uom_quantity',
        ];
        $actualHeadings = $headings[0][0];
        foreach ($expectedHeadings as $heading) {
            if (!in_array($heading, $actualHeadings)) {
                return ResponseData($data = null, $status_code = 422, false, $extra_message = 'Missing Heading: ' . $heading);
            }
        }
        $import = new RawItemImport();
        $import->import($file);
        ResponseMessage('Raw Items Import Successfully', 200);
    }
}
