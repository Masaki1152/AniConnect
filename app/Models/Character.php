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
        $characters = Character::orderBy('id', 'ASC')->where(function ($query) use ($search) {
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
