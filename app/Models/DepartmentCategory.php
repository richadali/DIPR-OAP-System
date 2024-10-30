<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentCategory extends Model
{
    use HasFactory;
    protected $table = 'department_category';
    protected $fillable = ['category_name'];

    // Define the relationship with the Department model
    public function departments()
    {
        return $this->hasMany(Department::class);
    }
}
