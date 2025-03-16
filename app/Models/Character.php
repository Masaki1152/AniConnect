<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class Character extends Model
{
    use HasFactory;

    // 参照させたいcharactersを指定
    protected $table = 'characters';

    protected $casts = [
        'top_categories_updated_at' => 'datetime',
    ];

    // 登場人物の検索処理
    public function fetchCharacters($search, $categoryIds)
    {
        $characters = Character::with(['works', 'works.creator', 'voiceArtist'])
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
                                // リレーション先のWorksテーブルのカラムでの検索
                                ->orWhereHas('works', function ($workQuery) use ($search_word) {
                                    $workQuery->where('name', 'LIKE', "%{$search_word}%")
                                        ->orWhere('term', 'like', '%' . $search_word . '%')
                                        // リレーション先のCreatorsテーブルのカラムでの検索
                                        ->orWhereHas('creator', function ($creatorQuery) use ($search_word) {
                                            $creatorQuery->where('name', 'like', '%' . $search_word . '%');
                                        });
                                })
                                // リレーション先のvoice_artistsテーブルのカラムでの検索
                                ->orWhereHas('voiceArtist', function ($voiceArtistQuery) use ($search_word) {
                                    $voiceArtistQuery->where('name', 'LIKE', "%{$search_word}%");
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
        return $characters;
    }

    // カテゴリーIdの集計処理
    public function updateTopCategories()
    {
        // 作品ごとに各カテゴリーとその出現回数を取得
        $topCategoriesData = DB::table('character_post_category')
            ->join('character_posts', 'character_posts.id', '=', 'character_post_category.character_post_id')
            ->join('characters', 'characters.id', '=', 'character_posts.character_id')
            ->select(
                'characters.id as character_id',
                'character_post_category.character_post_category_id',
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('character_id', 'character_post_category.character_post_category_id')
            ->orderBy('character_id')
            ->orderByDesc('count')
            ->orderBy('character_post_category.character_post_category_id', 'asc')
            ->get()
            ->groupBy('character_id');

        // 作品ごとに上位3つのカテゴリを抽出して更新
        foreach ($topCategoriesData as $characterId => $categories) {

            // キャッシュキーの作成
            $cacheKey = "character_top_categories_{$characterId}";
            // 上位3つのカテゴリを抽出
            $topCategories = $categories->take(3)->pluck('character_post_category_id')->toArray();

            // カテゴリを更新
            Character::where('id', $characterId)->update([
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

        Character::whereNotIn('id', $updatedIds)->update([
            'category_top_1' => null,
            'category_top_2' => null,
            'category_top_3' => null,
            'top_categories_updated_at' => now(),
        ]);
    }

    // Workに対するリレーション 多対多の関係
    public function works()
    {
        return $this->belongsToMany(Work::class, 'character_work', 'character_id', 'work_id');
    }

    // VoiceArtistに対するリレーション 1対多の関係
    public function voiceArtist()
    {
        return $this->belongsTo(VoiceArtist::class, 'voice_artist_id', 'id');
    }

    // CharacterPostに対するリレーション 1対1の関係
    public function characterPost()
    {
        return $this->hasOne(CharacterPost::class, 'id', 'character_id');
    }

    // 気になるをしたUserに対するリレーション　多対多の関係
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_character', 'character_id', 'user_id')
            ->withPivot('created_at');
    }
}
