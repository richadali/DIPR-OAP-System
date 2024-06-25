<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $table = 'bills';
    protected $fillable =['bill_no','bill_date','ad_id','paid_by'];

    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class, 'ad_id');
    }

    public function bill_type()
    {
        return $this->belongsTo(BillType::class);
    }

    public function bill_no()
    {
        return $this->hasOne(BillNo::class);
    }

    public function empanelled()
    {
        return $this->belongsTo(Empanelled::class,'empanelled_id');
    }
}
