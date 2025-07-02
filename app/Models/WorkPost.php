<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkPost extends Model
{
    use HasFactory;
    use SerializeDate;

    // fillを実行するための記述
    protected $fillable = [
        'work_id',
        'user_id',
        'post_title',
        'star_num',
        'body',
        'image1',
        'image2',
        'image3',
        'image4',
    ];

    // 参照させたいwork_postsを指定
    protected $table = 'work_posts';

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // 作品投稿の検索処理
    public function fetchWorkPosts($work_id, $search, $categoryIds)
    {
        // 指定したidのアニメの投稿のみを表示
        $work_posts = WorkPost::where('work_id', $work_id)
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
                            $categoryQuery->where('work_post_categories.id', $categoryId);
                        });
                    }
                }
            })
            ->orderBy('created_at', 'DESC')
            ->paginate(5);
        return $work_posts;
    }

    // created_atで降順に並べたあと、limitで件数制限をかける
    public function getPaginateByLimit($work_id, int $limit_count = 5)
    {
        return $this->where('work_id', $work_id)->orderBy('created_at', 'DESC')->paginate($limit_count);
    }

    // 作品idと投稿idを指定して、投稿の詳細表示を行う
    public function getDetailPost($work_id, $work_post_id)
    {
        return $this->where([
            ['work_id', $work_id],
            ['id', $work_post_id],
        ])->first();
    }

    // 条件とその値を指定してデータを1件取得する
    public function getRestrictedPost($condition, $column_name)
    {
        return $this->where($condition, $column_name)->first();
    }

    // Workに対するリレーション 1対1の関係
    public function work()
    {
        return $this->belongsTo(Work::class);
    }

    // Userに対するリレーション 1対1の関係
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // カテゴリーに対するリレーション 多対多の関係
    public function categories()
    {
        return $this->belongsToMany(WorkPostCategory::class, 'work_post_work_post_category', 'work_post_id', 'work_post_category_id');
    }

    // いいねをしたUserに対するリレーション　多対多の関係
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_work_post', 'work_post_id', 'user_id')
            ->withPivot('created_at');
    }

    // コメントに対するリレーション 一対多の関係
    public function workPostComments()
    {
        return $this->hasMany(WorkPostComment::class, 'work_post_id', 'id');
    }
}
