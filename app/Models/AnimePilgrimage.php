<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class AnimePilgrimage extends Model
{
    use HasFactory;

    // 参照させたいanime_pilgrimagesを指定
    protected $table = 'anime_pilgrimages';

    protected $casts = [
        'top_categories_updated_at' => 'datetime',
    ];

    // 聖地の検索処理
    public function fetchAnimePilgrimages($search, $prefecture_search, $categoryIds)
    {
        $pilgrimages = AnimePilgrimage::with(['works', 'works.characters', 'animePilgrimagePosts'])
            ->where(function ($query) use ($search, $prefecture_search, $categoryIds) {
                // キーワード検索がなされた場合
                if ($search) {
                    // 検索語のスペースを半角に統一
                    $search_split = mb_convert_kana($search, 's');
                    // 半角スペースで単語ごとに分割して配列にする
                    $search_array = preg_split('/[\s]+/', $search_split);
                    foreach ($search_array as $search_word) {
                        // 自身のカラムでの検索
                        $query->where(function ($query) use ($search_word) {
                            $query->where('name', 'LIKE', "%{$search_word}%")
                                ->orWhere('place', 'LIKE', "%{$search_word}%")
                                // リレーション先のWorksテーブルのカラムでの検索
                                ->orWhereHas('works', function ($workQuery) use ($search_word) {
                                    $workQuery->where('name', 'LIKE', "%{$search_word}%")
                                        ->orWhere('term', 'like', '%' . $search_word . '%')
                                        // リレーション先のCharactersテーブルのカラムでの検索
                                        ->orWhereHas('characters', function ($characterQuery) use ($search_word) {
                                            $characterQuery->where('name', 'like', '%' . $search_word . '%');
                                        });
                                })
                                // リレーション先のAnimePilgrimagePostsテーブルのカラムでの検索
                                ->orWhereHas('animePilgrimagePosts', function ($pilgrimagePostQuery) use ($search_word) {
                                    $pilgrimagePostQuery->where('scene', 'LIKE', "%{$search_word}%");
                                });
                        });
                    }
                }

                // 県名検索がなされた場合
                if ($prefecture_search) {
                    $query->where('prefecture_id', $prefecture_search);
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
            ->paginate(5);
        return $pilgrimages;
    }

    // カテゴリーIdの集計処理
    public function updateTopCategories()
    {
        // 作品ごとに各カテゴリーとその出現回数を取得
        $topCategoriesData = DB::table('pilgrimage_post_category')
            ->join('anime_pilgrimage_posts', 'anime_pilgrimage_posts.id', '=', 'pilgrimage_post_category.anime_pilgrimage_post_id')
            ->join('anime_pilgrimages', 'anime_pilgrimages.id', '=', 'anime_pilgrimage_posts.anime_pilgrimage_id')
            ->select(
                'anime_pilgrimages.id as pilgrimage_id',
                'pilgrimage_post_category.pilgrimage_post_category_id',
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('pilgrimage_id', 'pilgrimage_post_category.pilgrimage_post_category_id')
            ->orderBy('pilgrimage_id')
            ->orderByDesc('count')
            ->orderBy('pilgrimage_post_category.pilgrimage_post_category_id', 'asc')
            ->get()
            ->groupBy('pilgrimage_id');

        // 作品ごとに上位3つのカテゴリを抽出して更新
        foreach ($topCategoriesData as $pilgrimageId => $categories) {

            // キャッシュキーの作成
            $cacheKey = "pilgrimage_top_categories_{$pilgrimageId}";
            // 上位3つのカテゴリを抽出
            $topCategories = $categories->take(3)->pluck('pilgrimage_post_category_id')->toArray();

            // カテゴリを更新
            AnimePilgrimage::where('id', $pilgrimageId)->update([
                'category_top_1' => $topCategories[0] ?? null,
                'category_top_2' => $topCategories[1] ?? null,
                'category_top_3' => $topCategories[2] ?? null,
                'top_categories_updated_at' => now()
            ]);

            // キャッシュを更新
            Cache::put($cacheKey, $topCategories, now()->addHours(3));
        }

        // カテゴリーが存在しない登場人物も `top_categories_updated_at` を更新
        $updatedIds = $topCategoriesData->keys()->toArray();

        AnimePilgrimage::whereNotIn('id', $updatedIds)->update([
            'category_top_1' => null,
            'category_top_2' => null,
            'category_top_3' => null,
            'top_categories_updated_at' => now(),
        ]);
    }

    // Workに対するリレーション 多対多の関係
    public function works()
    {
        return $this->belongsToMany(Work::class, 'anime_pilgrimage_work', 'anime_pilgrimage_id', 'work_id');
    }

    // Prefectureに対するリレーション 1対多の関係
    public function prefectures()
    {
        return $this->belongsTo(Prefecture::class, 'prefecture_id', 'id');
    }

    // AnimePilgrimagePostに対するリレーション 1対多の関係
    public function animePilgrimagePosts()
    {
        return $this->hasMany(AnimePilgrimagePost::class, 'anime_pilgrimage_id',  'id');
    }
}
