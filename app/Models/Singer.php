<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Singer extends Model
{
    use HasFactory;

    // 参照させたいsingersを指定
    protected $table = 'singers';

    // Musicに対するリレーション 1対多の関係
    public function music()
    {
        return $this->hasMany(Music::class, 'singer_id', 'id');
    }
}
