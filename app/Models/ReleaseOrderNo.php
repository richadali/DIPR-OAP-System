<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReleaseOrderNo extends Model
{
    use HasFactory;
    protected $table = 'release_order_no';
    protected $fillable = ['release_order_no', 'fin_year'];
    protected $primaryKey = ['release_order_no', 'fin_year']; // Define the composite primary key
    public $timestamps = false;

    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class);
    }
}
