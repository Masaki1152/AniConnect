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
        'created_at' => 'datetime',
    ];

    // 登場人物投稿の検索処理
    public function fetchCharacterPosts($character_id, $search, $categoryIds)
    {
        // 指定したidの登場人物の投稿のみを表示
        $character_posts = CharacterPost::where('character_id', $character_id)
            ->with(['user', 'categories'])
            ->where(function ($query) use ($search, $categoryIds) {
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
                                ->orWhere('body', 'LIKE', "%{$search_word}%")
                                // リレーション先のUsersテーブルのカラムでの検索
                                ->orWhereHas('user', function ($userQuery) use ($search_word) {
                                    $userQuery->where('name', 'like', '%' . $search_word . '%');
                                });
                        });
                    }
                }

                // クリックされたカテゴリーIdがある場合
                if (!empty($categoryIds)) {
                    foreach ($categoryIds as $categoryId) {
                        $query->whereHas('categories', function ($categoryQuery) use ($categoryId) {
                            $categoryQuery->where('character_post_categories.id', $categoryId);
                        });
                    }
                }
            })
            ->orderBy('created_at', 'DESC')
            ->paginate(5);
        return $character_posts;
    }

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
        return $this->belongsToMany(User::class, 'character_posts_users', 'character_post_id', 'user_id')
            ->withPivot('created_at');
    }

    // コメントに対するリレーション 一対多の関係
    public function characterPostComments()
    {
        return $this->hasMany(CharacterPostComment::class, 'character_post_id', 'id');
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
