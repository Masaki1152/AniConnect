<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Composer extends Model
{
    use HasFactory;

    // 参照させたいcomposersを指定
    protected $table = 'composers';

    // Musicに対するリレーション 1対多の関係
    public function music()
    {
        return $this->hasMany(Music::class, 'composer_id', 'id');
    }
}
