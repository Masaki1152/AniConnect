<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    use HasFactory;

    // 参照させたいworksを指定
    protected $table = 'works';

    // WorkReviewに対するリレーション 1対1の関係
    public function workreview()
    {
        return $this->belongsTo(WorkReview::class);
    }

    // AnimePilgrimageに対するリレーション 1対多の関係
    public function animePilgrimages()
    {
        return $this->hasMany(AnimePilgrimage::class, 'work_id', 'id');
    }

    // Characterに対するリレーション 1対多の関係
    public function characters()
    {
        return $this->hasMany(Character::class, 'work_id', 'id');
    }

    // Creatorに対するリレーション 1対多の関係
    public function creator()
    {
        return $this->belongsTo(Creator::class, 'creator_id');
    }

    // Musicに対するリレーション 1対多の関係
    public function music()
    {
        return $this->hasMany(Music::class, 'work_id', 'id');
    }

    // created_atで降順に並べたあと、limitで件数制限をかける
    public function getPaginateByLimit(int $limit_count = 5)
    {
        return $this->orderBy('id', 'ASC')->paginate($limit_count);
    }
}
