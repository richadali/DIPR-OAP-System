<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MiprNo extends Model
{
    use HasFactory;

    protected $table = 'mipr_no';
    protected $fillable = ['mipr_no', 'fin_year'];
    public $timestamps = false;

    // No need to define composite primary keys
    public $incrementing = false; // Disable auto-incrementing if not using an auto-incrementing primary key

    protected $primaryKey = ['mipr_no', 'fin_year']; // Keep it here for clarity, but it's not used by Eloquent

    // Define relationships if needed
    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class);
    }
}
