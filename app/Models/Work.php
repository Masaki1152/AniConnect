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
        $works = Work::orderBy('id', 'ASC')->where(function ($query) use ($search) {
            // キーワード検索がなされた場合
            if ($search) {
                // 検索語のスペースを半角に統一
                $search_split = mb_convert_kana($search, 's');
                // 半角スペースで単語ごとに分割して配列にする
                $search_array = preg_split('/[\s]+/', $search_split);
                foreach ($search_array as $search_word) {
                    $query->where(function ($query) use ($search_word) {
                        $query->where('name', 'LIKE', "%{$search_word}%");
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

    // AnimePilgrimageに対するリレーション 1対多の関係
    public function animePilgrimages()
    {
        return $this->hasMany(AnimePilgrimage::class, 'work_id', 'id');
    }

    // Characterに対するリレーション 1対多の関係
    public function characters()
    {
        return $this->hasMany(Character::class, 'work_id', 'id');
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
