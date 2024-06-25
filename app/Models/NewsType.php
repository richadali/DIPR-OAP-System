<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsType extends Model
{
    use HasFactory;
    protected $table = 'newspaper_type';

    public function empanelled()
    {
        return $this->hasMany(Empanelled::class);
    }

    public static function getAll()
    {
        return parent::orderBy('news_type', 'asc')->get();
    }
}
