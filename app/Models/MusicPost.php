<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MusicPost extends Model
{
    use HasFactory;

    // 参照させたいmusic_postsを指定
    protected $table = 'music_posts';

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
