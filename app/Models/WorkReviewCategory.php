<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkReviewCategory extends Model
{
    use HasFactory;

    // 参照させたいwork_reviewsを指定
    protected $table = 'work_review_categories';

    // WorkReviewに対するリレーション 多対多の関係
    public function workreviews()
    {
        return $this->belongsToMany(WorkReview::class);
    }
}
