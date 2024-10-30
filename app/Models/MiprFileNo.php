<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MiprFileNo extends Model
{
    use HasFactory;
    protected $table = 'mipr_file_no';
    protected $fillable = ['mipr_file_no'];
}
