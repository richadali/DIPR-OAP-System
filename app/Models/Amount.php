<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amount extends Model
{
    use HasFactory;

    protected $table = 'amount';

    protected $fillable = [
        'advertisement_type_id',
        'ad_category_id',
        'amount',
    ];

    public function adCategory()
    {
        return $this->belongsTo(AdCategory::class);
    }

    public function advertisementType()
    {
        return $this->belongsTo(AdvertisementType::class);
    }
}
