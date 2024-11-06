<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnimePilgrimage extends Model
{
    use HasFactory;

    // 参照させたいanime_pilgrimagesを指定
    protected $table = 'anime_pilgrimages';

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
