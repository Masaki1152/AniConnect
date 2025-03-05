<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class WorkStory extends Model
{
    use HasFactory;

    // 参照させたいwork_storiesを指定
    protected $table = 'work_stories';

    protected $casts = [
        'top_categories_updated_at' => 'datetime',
    ];

    // あらすじの検索処理
    public function fetchWorkStories($search, $work_id, $categoryIds)
    {
        $work_stories = WorkStory::where('work_id', '=', $work_id)
            ->where(function ($query) use ($search, $categoryIds) {

                // キーワード検索がなされた場合
                if ($search) {
                    // 検索語のスペースを半角に統一
                    $search_split = mb_convert_kana($search, 's');
                    // 半角スペースで単語ごとに分割して配列にする
                    $search_array = preg_split('/[\s]+/', $search_split);
                    foreach ($search_array as $search_word) {
                        $query->where(function ($query) use ($search_word) {
                            $query->where('episode', 'LIKE', "%{$search_word}%")
                                ->orWhere('sub_title', 'LIKE', "%{$search_word}%")
                                ->orWhere('body', 'LIKE', "%{$search_word}%");
                        });
                    }
                }

                // クリックされたカテゴリーIdがある場合
                if (!empty($categoryIds)) {
                    $query->where(function ($categoryQuery) use ($categoryIds) {
                        foreach ($categoryIds as $categoryId) {
                            $categoryQuery->where(function ($innerQuery) use ($categoryId) {
                                $innerQuery->where('category_top_1', $categoryId)
                                    ->orWhere('category_top_2', $categoryId)
                                    ->orWhere('category_top_3', $categoryId);
                            });
                        }
                    });
                }
            })
            ->orderBy('id', 'ASC')
            ->paginate(20);
        return $work_stories;
    }

    // カテゴリーIdの集計処理
    public function updateTopCategories()
    {
        // 作品ごとに各カテゴリーとその出現回数を取得
        $topCategoriesData = DB::table('work_story_post_category')
            ->join('work_story_posts', 'work_story_posts.id', '=', 'work_story_post_category.work_story_post_id')
            ->join('work_stories', 'work_stories.id', '=', 'work_story_posts.sub_title_id')
            ->select(
                'work_stories.id as work_story_id',
                'work_story_post_category.work_story_post_category_id',
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('work_story_id', 'work_story_post_category.work_story_post_category_id')
            ->orderBy('work_story_id')
            ->orderByDesc('count')
            ->orderBy('work_story_post_category.work_story_post_category_id', 'asc')
            ->get()
            ->groupBy('work_story_id');

        // 作品ごとに上位3つのカテゴリを抽出して更新
        foreach ($topCategoriesData as $workStoryId => $categories) {

            // キャッシュキーの作成
            $cacheKey = "work_story_top_categories_{$workStoryId}";
            // 上位3つのカテゴリを抽出
            $topCategories = $categories->take(3)->pluck('work_story_post_category_id')->toArray();

            // カテゴリを更新
            WorkStory::where('id', $workStoryId)->update([
                'category_top_1' => $topCategories[0] ?? null,
                'category_top_2' => $topCategories[1] ?? null,
                'category_top_3' => $topCategories[2] ?? null,
                'top_categories_updated_at' => now()
            ]);

            // キャッシュを更新
            Cache::put($cacheKey, $topCategories, now()->addHours(3));
        }

        // カテゴリーが存在しない作品も `top_categories_updated_at` を更新
        $updatedIds = $topCategoriesData->keys()->toArray();

        WorkStory::whereNotIn('id', $updatedIds)->update([
            'category_top_1' => null,
            'category_top_2' => null,
            'category_top_3' => null,
            'top_categories_updated_at' => now(),
        ]);
    }

    // Workに対するリレーション 1対多の関係
    public function work()
    {
        return $this->belongsTo(Work::class, 'work_id', 'id');
    }

    // WorkStoryPostに対するリレーション 1対1の関係
    public function workStoryPost()
    {
        return $this->hasOne(WorkStoryPost::class, 'id', 'sub_title_id');
    }

    // 気になるをしたUserに対するリレーション　多対多の関係
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_work_story', 'work_story_id', 'user_id')
            ->withPivot('created_at');
    }
}
