<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class BillNo extends Model
{
    use HasFactory;
    protected $table = 'bill_no';
    protected $fillable = ['bill_no', 'fin_year'];
    protected $primaryKey = ['bill_no', 'fin_year']; // Define the composite primary key
    public $timestamps = false;

    public funcTION bill()
    {
        return $this->belongsTo(Bill::class);
    }
}
