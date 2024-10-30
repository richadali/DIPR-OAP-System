<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    use HasFactory;

    // protected $dispatchesEvents = [
    //     'saved' => AdvertisementSaved::class,
    // ];

    protected $table = 'advertisement';

    protected $fillable = [
        'release_order_no',
        'release_order_date',
        'payment_by',
        'department',
        'mipr_no'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function advertisement_type()
    {
        return $this->belongsTo(AdvertisementType::class, 'advertisement_type_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function ad_category()
    {
        return $this->belongsTo(AdCategory::class, 'ad_category_id');
    }

    public function assigned_news()
    {
        return $this->hasMany(AssignedNews::class, 'advertisement_id');
    }

    public function app_track()
    {
        return $this->hasMany(AppTrack::class);
    }

    public function bill()
    {
        return $this->hasMany(Bill::class);
    }

    public function release_order()
    {
        return $this->hasOne(ReleaseOrderNo::class);
    }

    public function mipr_no()
    {
        return $this->hasOne(MiprNo::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id');
    }

    public function page_info()
    {
        return $this->belongsTo(PageInfo::class, 'page_info_id');
    }
    
}
