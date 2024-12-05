<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnimePilgrimagePost extends Model
{
    use HasFactory;
    use SerializeDate;

    // fillを実行するための記述
    protected $fillable = [
        'user_id',
        'anime_pilgrimage_id',
        'post_title',
        'scene',
        'body',
        'image1',
        'image2',
        'image3',
        'image4',
    ];

    // 参照させたいanime_pilgrimage_postsを指定
    protected $table = 'anime_pilgrimage_posts';

    protected $casts = [
        'created_at' => 'datetime:Y/m/d H:i',
    ];

    // 聖地idと投稿idを指定して、投稿の詳細表示を行う
    public function getDetailPost($pilgrimage_id, $pilgrimage_post_id)
    {
        return $this->where([
            ['anime_pilgrimage_id', $pilgrimage_id],
            ['id', $pilgrimage_post_id],
        ])->first();
    }

    // 条件とその値を指定してデータを1件取得する
    public function getRestrictedPost($condition, $column_name)
    {
        return $this->where($condition, $column_name)->first();
    }

    // AnimePilgrimageに対するリレーション 1対1の関係
    public function animePilgrimage()
    {
        return $this->belongsTo(AnimePilgrimage::class, 'anime_pilgrimage_id', 'id');
    }

    // 投稿者に対するリレーション 1対1の関係
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // いいねをしたUserに対するリレーション　多対多の関係
    public function users()
    {
        return $this->belongsToMany(User::class, 'anime_pilgrimage_posts_users', 'anime_pilgrimage_post_id', 'user_id');
    }
}
