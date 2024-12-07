<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MusicPost extends Model
{
    use HasFactory;
    use SerializeDate;

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

    protected $casts = [
        'created_at' => 'datetime:Y/m/d H:i',
    ];

    // 音楽投稿の検索処理
    public function fetchMusicPosts($music_id, $search)
    {
        // 指定したidの音楽の投稿のみを表示
        $music_posts = MusicPost::where('music_id', $music_id)->orderBy('id', 'DESC')
            ->with(['user'])
            ->where(function ($query) use ($search) {
                // キーワード検索がなされた場合
                if ($search) {
                    // 検索語のスペースを半角に統一
                    $search_split = mb_convert_kana($search, 's');
                    // 半角スペースで単語ごとに分割して配列にする
                    $search_array = preg_split('/[\s]+/', $search_split);
                    foreach ($search_array as $search_word) {
                        // 自身のカラムでの検索
                        $query->where(function ($query) use ($search_word) {
                            $query->where('post_title', 'LIKE', "%{$search_word}%")
                                ->orWhere('body', 'LIKE', "%{$search_word}%");
                        });

                        // リレーション先のUsersテーブルのカラムでの検索
                        $query->orWhereHas('user', function ($userQuery) use ($search_word) {
                            $userQuery->where('name', 'like', '%' . $search_word . '%');
                        });
                    }
                }
            })->paginate(5);
        return $music_posts;
    }

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
