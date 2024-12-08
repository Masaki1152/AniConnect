<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    use HasFactory;

    // 参照させたいworksを指定
    protected $table = 'works';

    // 作品の検索処理
    public function fetchWorks($search)
    {
        $works = Work::orderBy('id', 'ASC')
            ->with(['creator', 'animePilgrimages', 'characters', 'characters.voiceArtist', 'music', 'music.singer', 'workStories'])
            ->where(function ($query) use ($search) {
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
            })->paginate(5);
        return $works;
    }

    // WorkReviewに対するリレーション 1対1の関係
    public function workreview()
    {
        return $this->belongsTo(WorkReview::class);
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
}
