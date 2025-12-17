<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Advance extends Model
{
    //
    protected $fillable = ['date_time','advance_ref_no','staff_id','advance_amount','total_months','remaining_amount','remaining_months','current_deduction_amount'];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($advance) {

            $staffId = $advance->staff_id;
            $datetime = str_pad(now()->timestamp, 10, '0', STR_PAD_LEFT);
            $random = str_pad(random_int(0, 9999999999), 10, '0', STR_PAD_LEFT);
            $advance->advance_ref_no = 'ADV' . '-' .$staffId .$datetime . Str::ulid();;
        });
    }
    public function staff()  {
      return $this->belongsTo(Staff::class);
    }
    public function advance_payment(){
        return $this->hasMany(AdvancePayment::class);
    }
}
