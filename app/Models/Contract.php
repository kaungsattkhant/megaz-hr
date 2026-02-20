<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected  $fillable = [
        'role_id',
        'company_authorizer_id',
        'contract_category_id',
        "type",
        "witness_id",
        "text",
    ];
    public function contract_category()
    {
        return $this->belongsTo(ContractCategory::class);
    }
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function company_authorizer()
    {
        return $this->belongsTo(Staff::class);
    }
    public function witness()
    {
        return $this->belongsTo(Staff::class);
    }

    public function contract_staff()
    {
        return $this->belongsToMany(ContractStaff::class, 'contract_staff', 'contract_id', 'staff_id');
    }
}
