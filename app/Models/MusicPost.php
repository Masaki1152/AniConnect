<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MusicPost extends Model
{
    use HasFactory;

    // fillを実行するための記述
    protected $fillable = [
        'music_id',
        'user_id',
        'work_id',
        'post_title',
        'star_num',
        'body',
    ];

    // 参照させたいmusic_postsを指定
    protected $table = 'music_posts';

    // Musicに対するリレーション 1対1の関係
    public function music()
    {
        return $this->belongsTo(Music::class, 'music_id', 'id');
    }

    // 投稿者に対するリレーション 1対1の関係
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // 作品に対するリレーション 1対1の関係
    public function work()
    {
        return $this->belongsTo(Work::class, 'work_id', 'id');
    }

    // いいねをしたUserに対するリレーション　多対多の関係
    public function users()
    {
        return $this->belongsToMany(User::class, 'music_posts_users', 'music_post_id', 'user_id');
    }

    // 音楽idと投稿idを指定して、投稿の詳細表示を行う
    public function getDetailPost($music_id, $music_post_id)
    {
        return $this->where([
            ['music_id', $music_id],
            ['id', $music_post_id],
        ])->first();
    }

    // 条件とその値を指定してデータを1件取得する
    public function getRestrictedPost($condition, $column_name)
    {
        return $this->where($condition, $column_name)->first();
    }
}
