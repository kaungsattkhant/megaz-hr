<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable=['date','description','transactionable_id','transactionable_type','is_confirmed','created_by','inventory_id','is_closing','closing_date'];

    protected static function booted()
    {
        static::addGlobalScope('dateFilter', function (Builder $builder) {
            $builder
            // ->when(request()->filled('is_confirmed'), function ($query) {
            //     $query->isConfirmed(request('is_confirmed'));
            // })
            ->when(request()->has(['from_date', 'to_date']), function ($query) {
                $fromDate = convertDateFormat(request('from_date'));
                $toDate = convertDateFormat(request('to_date'));

                $query->whereBetween(DB::raw('DATE(date)'), [$fromDate, $toDate]);
            })->when(request()->has('from_date') && !request()->has('to_date'), function ($query) {
                $fromDate = convertDateFormat(request('from_date'));
                $query->whereDate('date', '>=', $fromDate);
            })->when(request()->has('to_date') && !request()->has('from_date'), function ($query) {
                $toDate = convertDateFormat(request('to_date'));
                $query->whereDate('date', '<=', $toDate);
            });
        });
    }

    public function transactionable(){
        return $this->morphTo();
    }
    
    public function ledgers(){
        return $this->hasMany(\App\Models\Ledger::class);
    }

    public function initialLedger(){
        return $this->hasOne(\App\Models\Ledger::class)->orderBy('id', 'asc');
    }
    public function finalLedger(){
        return $this->hasOne(\App\Models\Ledger::class)->orderBy('id', 'desc');
    }

    public function supplier(){
        return $this->belongsTo(\App\Models\Supplier::class);
    }

    public function scopeIsConfirmed($query,$bool){
        $isConfirmed=[$bool];
        if($bool=="-1"){
            $isConfirmed=[1,0];
        }
        return $query->whereIn('is_confirmed',$isConfirmed);
    }


}
