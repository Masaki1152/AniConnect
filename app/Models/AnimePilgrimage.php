<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnimePilgrimage extends Model
{
    use HasFactory;

    // 参照させたいanime_pilgrimagesを指定
    protected $table = 'anime_pilgrimages';

    // 聖地の検索処理
    public function fetchAnimePilgrimages($search, $prefecture_search)
    {
        $pilgrimages = AnimePilgrimage::orderBy('id', 'ASC')->where(function ($query) use ($search, $prefecture_search) {
            // キーワード検索がなされた場合
            if ($search) {
                // 検索語のスペースを半角に統一
                $search_split = mb_convert_kana($search, 's');
                // 半角スペースで単語ごとに分割して配列にする
                $search_array = preg_split('/[\s]+/', $search_split);
                foreach ($search_array as $search_word) {
                    $query->where(function ($query) use ($search_word) {
                        $query->where('name', 'LIKE', "%{$search_word}%")
                            ->orWhere('place', 'LIKE', "%{$search_word}%");
                    });
                }
            }

            // 県名検索がなされた場合
            if ($prefecture_search) {
                $query->where('prefecture_id', $prefecture_search);
            }
        })->paginate(5);
        return $pilgrimages;
    }

    // Workに対するリレーション 1対多の関係
    public function work()
    {
        return $this->belongsTo(Work::class, 'work_id', 'id');
    }

    // Prefectureに対するリレーション 1対多の関係
    public function prefectures()
    {
        return $this->belongsTo(Prefecture::class, 'prefecture_id', 'id');
    }

    // AnimePilgrimagePostに対するリレーション 1対1の関係
    public function animePilgrimagePost()
    {
        return $this->hasOne(AnimePilgrimagePost::class, 'id', 'anime_pilgrimage_id');
    }
}
