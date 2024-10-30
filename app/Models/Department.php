<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    protected $table = 'department';

    // Define the relationship with the DepartmentCategory model
    public function category()
    {
        return $this->belongsTo(DepartmentCategory::class, 'category_id');
    }
    public function advertisement()
    {
        return $this->hasMany(Advertisement::class, 'department_id');
    }

}
