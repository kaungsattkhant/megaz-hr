<?php

namespace App\Http\Action\Common;

use App\Models\UomConversion;

class Conversion
{

    private $base_uom_id,$conversion_uom_id;

    public function __construct(int $base_uom_id,int $conversion_uom_id)
    {
        $this->base_uom_id = $base_uom_id;
        $this->conversion_uom_id = $conversion_uom_id;
    }

    public function run(){
        $uom_conversion=UomConversion::where('base_unit_id',$this->base_uom_id)
        ->where('conversion_unit_id',$this->conversion_uom_id)->first();
        if(!$uom_conversion){
            ResponseMessage('Uom Conversion is required',419);
        }
        return $uom_conversion;
    }
}
