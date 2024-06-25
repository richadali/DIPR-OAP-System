<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppTrack extends Model
{
    use HasFactory;
    protected $table ='app_track';

    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class);
    }
}
