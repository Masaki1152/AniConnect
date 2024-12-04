<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CharacterPost extends Model
{
    use HasFactory;
    use SerializeDate;

    // fillを実行するための記述
    protected $fillable = [
        'work_id',
        'character_id',
        'user_id',
        'post_title',
        'star_num',
        'body',
        'image1',
        'image2',
        'image3',
        'image4',
    ];

    // 参照させたいcharacter_postsを指定
    protected $table = 'character_posts';

    protected $casts = [
        'created_at' => 'datetime:Y/m/d H:i',
    ];

    // Characterに対するリレーション 1対1の関係
    public function character()
    {
        return $this->belongsTo(Character::class, 'character_id', 'id');
    }

    // 投稿者に対するリレーション 1対1の関係
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // カテゴリーに対するリレーション 多対多の関係
    public function categories()
    {
        return $this->belongsToMany(CharacterPostCategory::class, 'character_post_category', 'character_post_id', 'character_post_category_id');
    }

    // いいねをしたUserに対するリレーション　多対多の関係
    public function users()
    {
        return $this->belongsToMany(User::class, 'character_posts_users', 'character_post_id', 'user_id');
    }

    // 登場人物idと投稿idを指定して、投稿の詳細表示を行う
    public function getDetailPost($character_id, $character_post_id)
    {
        return $this->where([
            ['character_id', $character_id],
            ['id', $character_post_id],
        ])->first();
    }

    // 条件とその値を指定してデータを1件取得する
    public function getRestrictedPost($condition, $column_name)
    {
        return $this->where($condition, $column_name)->first();
    }
}
