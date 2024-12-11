<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkStoryPostCategory extends Model
{
    use HasFactory;

    // 参照させたいwork_story_post_categoriesを指定
    protected $table = 'work_story_post_categories';

    // WorkStoryPostに対するリレーション 多対多の関係
    public function workStoryPosts()
    {
        return $this->belongsToMany(WorkStoryPost::class, 'work_story_post_category', 'work_story_post_category_id', 'work_story_post_id');
    }
}
