<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LyricWriter extends Model
{
    use HasFactory;

    // 参照させたいlyric_writersを指定
    protected $table = 'lyric_writers';

    // Musicに対するリレーション 1対多の関係
    public function music()
    {
        return $this->hasMany(Music::class, 'lyric_writer_id', 'id');
    }
}
