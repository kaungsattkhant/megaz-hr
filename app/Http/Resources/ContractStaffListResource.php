<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContractStaffListResource extends JsonResource
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
            'type'=>$this->contract->type,
            'text'=>$this->contract->text,
            'status' => $this->status,
            'signed_at' => $this->signed_at,
            'contract_category'=>[
                'id'=>$this->contract->contract_category->id,
                'name'=>$this->contract->contract_category->name,
            ],
            'staff'=>[
                'id'=>$this->staff->id,
                'name'=>$this->staff->name,
            ],
            
            // 'status'=>$this->status,
            // 'role'=>[
            //     'id'=>$this->role->id,
            //     'name'=>$this->role->name,
            // ],
            // 'company_authorizer'=>[
            //     'id'=>$this->company_authorizer->id,
            //     'name'=>$this->company_authorizer->name,
            // ],
            // 'witness'=>[
            //     'id'=>$this->witness->id,
            //     'name'=>$this->witness->name,
            // ],
        ];
    }
}
