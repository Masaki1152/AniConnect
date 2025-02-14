<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkStoryPost extends Model
{
    use HasFactory;
    use SerializeDate;

    // fillを実行するための記述
    protected $fillable = [
        'work_id',
        'user_id',
        'sub_title_id',
        'post_title',
        'star_num',
        'body',
        'image1',
        'image2',
        'image3',
        'image4',
    ];

    // 参照させたいwork_story_postsを指定
    protected $table = 'work_story_posts';

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // あらすじ投稿の検索処理
    public function fetchWorkStoryPosts($work_story_id, $search, $categoryIds)
    {
        // 指定したidのあらすじの投稿のみを表示
        $work_story_posts = WorkStoryPost::where('sub_title_id', $work_story_id)
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
                                ->orWhereHas('user', function ($userQuery) use ($search_word) {
                                    // リレーション先のUsersテーブルのカラムでの検索
                                    $userQuery->where('name', 'like', '%' . $search_word . '%');
                                });
                        });
                    }
                }

                // クリックされたカテゴリーIdがある場合
                if (!empty($categoryIds)) {
                    foreach ($categoryIds as $categoryId) {
                        $query->whereHas('categories', function ($categoryQuery) use ($categoryId) {
                            $categoryQuery->where('work_story_post_categories.id', $categoryId);
                        });
                    }
                }
            })
            ->orderBy('created_at', 'DESC')
            ->paginate(5);
        return $work_story_posts;
    }

    // あらすじidと投稿idを指定して、投稿の詳細表示を行う
    public function getDetailPost($work_story_id, $work_story_post_id)
    {
        return $this->where([
            ['sub_title_id', $work_story_id],
            ['id', $work_story_post_id],
        ])->first();
    }

    // 条件とその値を指定してデータを1件取得する
    public function getRestrictedPost($condition, $column_name)
    {
        return $this->where($condition, $column_name)->first();
    }

    // Work_Storyに対するリレーション 1対1の関係
    public function workStory()
    {
        return $this->belongsTo(WorkStory::class, 'sub_title_id', 'id');
    }

    // Workに対するリレーション 1対1の関係
    public function work()
    {
        return $this->belongsTo(Work::class, 'work_id', 'id');
    }

    // Userに対するリレーション 1対1の関係
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // カテゴリーに対するリレーション 多対多の関係
    public function categories()
    {
        return $this->belongsToMany(WorkStoryPostCategory::class, 'work_story_post_category', 'work_story_post_id', 'work_story_post_category_id');
    }

    // いいねをしたUserに対するリレーション　多対多の関係
    public function users()
    {
        return $this->belongsToMany(User::class, 'work_story_posts_users', 'work_story_post_id', 'user_id')
            ->withPivot('created_at');
    }

    // コメントに対するリレーション 一対多の関係
    public function workStoryPostComments()
    {
        return $this->hasMany(WorkStoryPostComment::class, 'work_story_post_id', 'id');
    }
}
