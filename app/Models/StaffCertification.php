<?php

namespace App\Models;

use App\Models\Staff;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StaffCertification extends Model
{
    use HasFactory;
    protected $fillable = [
        'staff_id',
        'certificate_file_url',
        'certificate_file_path',
    ];
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
