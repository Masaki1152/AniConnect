<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class Music extends Model
{
    use HasFactory;

    // 参照させたいmusicを指定
    protected $table = 'music';

    protected $casts = [
        'top_categories_updated_at' => 'datetime',
    ];

    // 音楽の検索処理
    public function fetchMusic($search, $categoryIds)
    {
        $music = Music::with(['work', 'work.creator', 'singer', 'composer', 'lyricWriter'])
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
                                ->orWhereHas('work', function ($workQuery) use ($search_word) {
                                    $workQuery->where('name', 'LIKE', "%{$search_word}%")
                                        ->orWhere('term', 'like', '%' . $search_word . '%')
                                        // リレーション先のCreatorsテーブルのカラムでの検索
                                        ->orWhereHas('creator', function ($creatorQuery) use ($search_word) {
                                            $creatorQuery->where('name', 'like', '%' . $search_word . '%');
                                        });
                                })

                                // リレーション先のsingersテーブルのカラムでの検索
                                ->orWhereHas('singer', function ($singerQuery) use ($search_word) {
                                    $singerQuery->where('name', 'LIKE', "%{$search_word}%");
                                })
                                // リレーション先のcomposersテーブルのカラムでの検索
                                ->orWhereHas('composer', function ($composerQuery) use ($search_word) {
                                    $composerQuery->where('name', 'LIKE', "%{$search_word}%");
                                })
                                // リレーション先のlyric_writersテーブルのカラムでの検索
                                ->orWhereHas('lyricWriter', function ($lyricWriterQuery) use ($search_word) {
                                    $lyricWriterQuery->where('name', 'LIKE', "%{$search_word}%");
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
        return $music;
    }

    // カテゴリーIdの集計処理
    public function updateTopCategories()
    {
        // 作品ごとに各カテゴリーとその出現回数を取得
        $topCategoriesData = DB::table('category_music_post')
            ->join('music_posts', 'music_posts.id', '=', 'category_music_post.music_post_id')
            ->join('music', 'music.id', '=', 'music_posts.music_id')
            ->select(
                'music.id as music_id',
                'category_music_post.music_post_category_id',
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('music_id', 'category_music_post.music_post_category_id')
            ->orderBy('music_id')
            ->orderByDesc('count')
            ->orderBy('category_music_post.music_post_category_id', 'asc')
            ->get()
            ->groupBy('music_id');

        // 作品ごとに上位3つのカテゴリを抽出して更新
        foreach ($topCategoriesData as $musicId => $categories) {

            // キャッシュキーの作成
            $cacheKey = "music_top_categories_{$musicId}";
            // 上位3つのカテゴリを抽出
            $topCategories = $categories->take(3)->pluck('music_post_category_id')->toArray();

            // カテゴリを更新
            Music::where('id', $musicId)->update([
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

        Music::whereNotIn('id', $updatedIds)->update([
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

    // Composerに対するリレーション 1対多の関係
    public function composer()
    {
        return $this->belongsTo(Composer::class, 'composer_id', 'id');
    }

    // LyricWriterに対するリレーション 1対多の関係
    public function lyricWriter()
    {
        return $this->belongsTo(LyricWriter::class, 'lyric_writer_id', 'id');
    }

    // Singerに対するリレーション 1対多の関係
    public function singer()
    {
        return $this->belongsTo(Singer::class, 'singer_id', 'id');
    }

    // MusicPostに対するリレーション 1対1の関係
    public function musicPost()
    {
        return $this->hasOne(MusicPost::class, 'id', 'music_id');
    }

    // 気になるをしたUserに対するリレーション　多対多の関係
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_music', 'music_id', 'user_id')
            ->withPivot('created_at');
    }
}
