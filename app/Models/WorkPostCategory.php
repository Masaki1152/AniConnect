<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkPostCategory extends Model
{
    use HasFactory;

    // 参照させたいwork_postsを指定
    protected $table = 'work_post_categories';

    // WorkPostに対するリレーション 多対多の関係
    public function workPosts()
    {
        return $this->belongsToMany(WorkPost::class);
    }
}
