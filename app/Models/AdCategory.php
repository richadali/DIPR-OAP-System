<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdCategory extends Model
{
    use HasFactory;

    protected $table = 'ad_category';

    public function advertisement() 
    {
        return $this->hasMany(Advertisement::class);
        return $this->belongsTo(DepartmentCategory::class, 'category_id');
    }
    public function amount()
    {
        return $this->hasOne(Amount::class);
    }
}
