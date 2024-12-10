<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MusicPostCategory extends Model
{
    use HasFactory;

    // 参照させたいmusic_post_categoriesを指定
    protected $table = 'music_post_categories';

    // MusicPostに対するリレーション 多対多の関係
    public function musicPosts()
    {
        return $this->belongsToMany(MusicPost::class, 'category_music_post', 'music_post_category_id', 'music_post_id');
    }
}
