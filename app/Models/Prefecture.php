<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prefecture extends Model
{
    use HasFactory;

    // 参照させたいprefecturesを指定
    protected $table = 'prefectures';

    // idとnameのみを取得
    public function getLists()
    {
        $prefectures = prefecture::pluck('name', 'id');
        return $prefectures;
    }

    // AnimePilgrimageに対するリレーション 1対多の関係
    public function animePilgrimages()
    {
        return $this->hasMany(AnimePilgrimage::class, 'prefecture_id', 'id');
    }
}
