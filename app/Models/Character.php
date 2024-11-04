<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    use HasFactory;

    // 参照させたいcharactersを指定
    protected $table = 'characters';

    // Workに対するリレーション 1対多の関係
    public function work()
    {
        return $this->belongsTo(Work::class, 'work_id', 'id');
    }

    // VoiceArtistに対するリレーション 1対多の関係
    public function voiceArtist()
    {
        return $this->belongsTo(VoiceArtist::class, 'voice_artist_id', 'id');
    }

    // CharacterPostに対するリレーション 1対1の関係
    public function characterPost()
    {
        return $this->belongsTo(CharacterPost::class, 'id', 'character_id');
    }
}
