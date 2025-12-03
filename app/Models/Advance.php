<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advance extends Model
{
    //
    protected $fillable = ['advance_ref_no','staff_id','advance_amount','total_months','remaining_amount','remaining_months','current_deduction_amount'];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($advance) {

            $staffId = $advance->staff_id;
            $datetime = str_pad(now()->timestamp, 10, '0', STR_PAD_LEFT);
            $random = str_pad(random_int(0, 9999999999), 10, '0', STR_PAD_LEFT);
            $advance->advance_ref_no = 'ADV' . '-' .$staffId . $datetime . $random;
        });
    }
}
