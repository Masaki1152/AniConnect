<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnimePilgrimagePostCategory extends Model
{
    use HasFactory;

    // 参照させたいmusic_post_categoriesを指定
    protected $table = 'pilgrimage_post_categories';

    // AnimePilgrimagePostに対するリレーション 多対多の関係
    public function animePilgrimagePosts()
    {
        return $this->belongsToMany(AnimePilgrimagePost::class, 'pilgrimage_post_category', 'pilgrimage_post_category_id', 'anime_pilgrimage_post_id');
    }
}
