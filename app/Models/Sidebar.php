<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sidebar extends Model
{
    use HasFactory;
    protected $table = 'sidebar';

    public function subMenus()
    {
        return $this->hasMany(SubMenu::class);
    }
}
