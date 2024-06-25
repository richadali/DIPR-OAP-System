<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignedNews extends Model
{
    use HasFactory;
    protected $table = 'assigned_news';

    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class);
    }

    public function empanelled()
    {
        return $this->belongsTo(Empanelled::class,'empanelled_id', 'id');
    }

}
