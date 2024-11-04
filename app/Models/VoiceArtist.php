<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoiceArtist extends Model
{
    use HasFactory;

    // 参照させたいvoice_artistsを指定
    protected $table = 'voice_artists';

    // Workに対するリレーション 1対多の関係
    public function characters()
    {
        return $this->hasMany(Character::class, 'voice_artist_id', 'id');
    }
}
