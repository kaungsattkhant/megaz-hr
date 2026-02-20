<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContractListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'type'=>$this->type,
            'text'=>$this->text,
            'contract_category'=>[
                'id'=>$this->contract_category->id,
                'name'=>$this->contract_category->name,
            ],
            'role'=>[
                'id'=>$this->role->id,
                'name'=>$this->role->name,
            ],
            'company_authorizer'=>[
                'id'=>$this->company_authorizer->id,
                'name'=>$this->company_authorizer->name,
            ],
            'witness'=>[
                'id'=>$this->witness->id,
                'name'=>$this->witness->name,
            ],
        ];
    }
}
