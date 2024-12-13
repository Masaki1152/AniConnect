<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkCategory extends Model
{
    use HasFactory;

    // 参照させたいwork_categoriesを指定
    protected $table = 'work_categories';
}
