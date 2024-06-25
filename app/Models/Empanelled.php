<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empanelled extends Model
{
    use HasFactory;

    protected $table = 'empanelled';
    
    public function assigned_news()
    {
        return $this->hasMany(AssignedNews::class);
    }

    public function news_type()
    {
        return $this->belongsTo(NewsType::class,'newspaper_type_id', 'id');
    }

    // public function bill()
    // {
    //     return $this->belongsTo(Bill::class);
    // }
    
}
