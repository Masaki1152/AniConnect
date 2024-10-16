<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Work_Reviews extends Model
{
    use HasFactory;
    // 参照させたいwork_reviewsを指定
    protected $table = 'work_reviews';
}
