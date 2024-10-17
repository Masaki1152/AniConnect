<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkReview extends Model
{
    use HasFactory;

    // fillを実行するための記述
    protected $fillable = [
        'work_id',
        'post_title',
        'user_id',
        'body',
    ];

    // 参照させたいwork_reviewsを指定
    protected $table = 'work_reviews';

    // created_atで降順に並べたあと、limitで件数制限をかける
    public function getPaginateByLimit(int $limit_count = 5)
    {
        return $this->orderBy('created_at', 'DESC')->paginate($limit_count);
    }
}
