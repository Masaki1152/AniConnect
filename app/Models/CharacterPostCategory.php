<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CharacterPostCategory extends Model
{
    use HasFactory;

    // 参照させたいcharacter_post_categoriesを指定
    protected $table = 'character_post_categories';

    // CharacterPostに対するリレーション 多対多の関係
    public function characterPosts()
    {
        return $this->belongsToMany(CharacterPost::class, 'character_post_category', 'character_post_category_id', 'character_post_id');
    }
}
