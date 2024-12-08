<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    use HasFactory;

    // 参照させたいcharactersを指定
    protected $table = 'characters';

    // 登場人物の検索処理
    public function fetchCharacters($search)
    {
        $characters = Character::orderBy('id', 'ASC')
            ->with(['works', 'works.creator', 'voiceArtist'])
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
            })->paginate(5);
        return $characters;
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
}
