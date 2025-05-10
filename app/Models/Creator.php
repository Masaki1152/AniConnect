<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CommonFunction;

class Creator extends Model
{
    use HasFactory;
    use CommonFunction;

    // fillを実行するための記述
    protected $fillable = [
        'name',
        'image',
        'official_site_link',
        'wiki_link',
        'twitter_link'
    ];

    // 参照させたいcreatorsを指定
    protected $table = 'creators';

    // Workに対するリレーション 1対多の関係
    public function works()
    {
        return $this->hasMany(Work::class, 'creator_id', 'id');
    }
}
