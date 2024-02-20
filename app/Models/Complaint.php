<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\ComplaintCategory;

class Complaint extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'complaint_category_id','title','description','posted_by','status'
    ];

    public function getCreatedAt()
    {
        return parent::getCreatedAt();
    }

    public function getUpdatedAt()
    {
        return parent::getUpdatedAt();
    }

    public function complaint_category()
    {
        return $this->belongsTo(ComplaintCategory::class);
    }

    public function postedBy()
    {
        return $this->belongsTo(Staff::class, 'posted_by');
    }

}
