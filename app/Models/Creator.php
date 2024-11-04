<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Creator extends Model
{
    use HasFactory;

    // 参照させたいcreatorsを指定
    protected $table = 'creators';

    // Workに対するリレーション 1対多の関係
    public function works()
    {
        return $this->hasMany(Work::class, 'creator_id', 'id');
    }
}
