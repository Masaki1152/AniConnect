<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Traits\CommonFunction;

class Work extends Model
{
    use HasFactory;
    use CommonFunction;

    // 参照させたいworksを指定
    protected $table = 'works';

    protected $casts = [
        'top_categories_updated_at' => 'datetime',
    ];

    // 作品の検索処理
    public function fetchWorks($search, $categoryIds)
    {
        $works = Work::with(['creator', 'animePilgrimages', 'characters', 'characters.voiceArtist', 'music', 'music.singer', 'workStories'])
            ->where(function ($query) use ($search, $categoryIds) {
                // キーワード検索がなされた場合
                if ($search) {
                    // 検索語のスペースを半角に統一
                    $search_split = mb_convert_kana($search, 's');
                    // 半角スペースで単語ごとに分割して配列にする
                    $search_array = preg_split('/[\s]+/', $search_split);
                    foreach ($search_array as $search_word) {
                        $query->where(function ($query) use ($search_word) {
                            // 自身のカラムでの検索
                            $query->where('name', 'LIKE', "%{$search_word}%")
                                // リレーション先のCreatorsテーブルのカラムでの検索
                                ->orWhereHas('creator', function ($creatorQuery) use ($search_word) {
                                    $creatorQuery->where('name', 'like', '%' . $search_word . '%');
                                })
                                // リレーション先のanime_pilgrimagesテーブルのカラムでの検索
                                ->orWhereHas('animePilgrimages', function ($animePilgrimageQuery) use ($search_word) {
                                    $animePilgrimageQuery->where('name', 'like', '%' . $search_word . '%')
                                        ->orWhere('place', 'LIKE', "%{$search_word}%");
                                })
                                // リレーション先のCharactersテーブルのカラムでの検索
                                ->orWhereHas('characters', function ($characterQuery) use ($search_word) {
                                    $characterQuery->where('name', 'like', '%' . $search_word . '%')
                                        // リレーション先のvoice_artistsテーブルのカラムでの検索
                                        ->orWhereHas('voiceArtist', function ($voiceArtistQuery) use ($search_word) {
                                            $voiceArtistQuery->where('name', 'LIKE', "%{$search_word}%");
                                        });
                                })
                                // リレーション先のMusicsテーブルのカラムでの検索
                                ->orWhereHas('music', function ($musicQuery) use ($search_word) {
                                    $musicQuery->where('name', 'like', '%' . $search_word . '%')
                                        // リレーション先のsingersテーブルのカラムでの検索
                                        ->orWhereHas('singer', function ($singerQuery) use ($search_word) {
                                            $singerQuery->where('name', 'LIKE', "%{$search_word}%");
                                        });
                                })
                                // リレーション先のWork_storiesテーブルのカラムでの検索
                                ->orWhereHas('workStories', function ($workStoryQuery) use ($search_word) {
                                    $workStoryQuery->where('sub_title', 'like', '%' . $search_word . '%')
                                        ->orWhere('episode', 'like', '%' . $search_word . '%')
                                        ->orWhere('body', 'like', '%' . $search_word . '%');
                                });
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
            ->paginate(5);

        return $works;
    }

    // カテゴリーIdの集計処理
    public function updateTopCategories()
    {
        // 作品ごとに各カテゴリーとその出現回数を取得
        $topCategoriesData = DB::table('work_review_work_review_category')
            ->join('work_reviews', 'work_reviews.id', '=', 'work_review_work_review_category.work_review_id')
            ->join('works', 'works.id', '=', 'work_reviews.work_id')
            ->select(
                'works.id as work_id',
                'work_review_work_review_category.work_review_category_id',
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('works.id', 'work_review_work_review_category.work_review_category_id')
            ->orderBy('works.id')
            ->orderByDesc('count')
            ->orderBy('work_review_work_review_category.work_review_category_id', 'asc')
            ->get()
            ->groupBy('work_id');

        // 作品ごとに上位3つのカテゴリを抽出して更新
        foreach ($topCategoriesData as $workId => $categories) {

            // キャッシュキーの作成
            $cacheKey = "work_top_categories_{$workId}";
            // 上位3つのカテゴリを抽出
            $topCategories = $categories->take(3)->pluck('work_review_category_id')->toArray();

            // カテゴリを更新
            Work::where('id', $workId)->update([
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

        Work::whereNotIn('id', $updatedIds)->update([
            'category_top_1' => null,
            'category_top_2' => null,
            'category_top_3' => null,
            'top_categories_updated_at' => now(),
        ]);
    }

    // 指定の投稿数以上の作品を取得
    public function fetchSufficientReviewNumWorks()
    {
        // 人気度を算出する際の最低投稿数
        $minReviewNum = 3;
        $sufficientReviewsWorks = Work::with(['workReviews' => function ($query) {
            $query->select('id', 'work_id', 'star_num', 'created_at');
        }])
            ->withCount('workReviews')
            ->having('work_reviews_count', '>=', $minReviewNum)
            ->get();
        return $sufficientReviewsWorks;
    }

    // WorkReviewに対するリレーション 1対多の関係
    public function workReviews()
    {
        return $this->hasMany(WorkReview::class, 'work_id');
    }

    // AnimePilgrimageに対するリレーション 多対多の関係
    public function animePilgrimages()
    {
        return $this->belongsToMany(AnimePilgrimage::class, 'anime_pilgrimage_work', 'work_id', 'anime_pilgrimage_id');
    }

    // Characterに対するリレーション 多対多の関係
    public function characters()
    {
        return $this->belongsToMany(Character::class, 'character_work', 'work_id', 'character_id');
    }

    // Creatorに対するリレーション 1対多の関係
    public function creator()
    {
        return $this->belongsTo(Creator::class, 'creator_id', 'id');
    }

    // Musicに対するリレーション 1対多の関係
    public function music()
    {
        return $this->hasMany(Music::class, 'work_id', 'id');
    }

    // WorkStoryに対するリレーション 1対多の関係
    public function workStories()
    {
        return $this->hasMany(WorkStory::class, 'work_id', 'id');
    }

    // WorkStoryPostに対するリレーション 1対1の関係
    public function workStoryPost()
    {
        return $this->belongsTo(WorkStoryPost::class, 'work_id', 'id');
    }

    // created_atで降順に並べたあと、limitで件数制限をかける
    public function getPaginateByLimit(int $limit_count = 5)
    {
        return $this->orderBy('id', 'ASC')->paginate($limit_count);
    }

    // 気になるをしたUserに対するリレーション　多対多の関係
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_work', 'work_id', 'user_id')
            ->withPivot('created_at');
    }
}
