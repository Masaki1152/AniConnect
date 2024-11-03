<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Music extends Model
{
    use HasFactory;

    // 参照させたいmusicを指定
    protected $table = 'music';

    // Workに対するリレーション 1対多の関係
    public function work()
    {
        return $this->belongsTo(Work::class, 'id', 'music_id');
    }
}
