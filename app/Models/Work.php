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
}
