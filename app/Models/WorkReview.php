<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkReview extends Model
{
    use HasFactory;
    // 参照させたいwork_reviewsを指定
    protected $table = 'work_reviews';

    // created_atで降順に並べたあと、limitで件数制限をかける
    public function getPaginateByLimit(int $limit_count = 5)
    {
        return $this->orderBy('created_at', 'DESC')->paginate($limit_count);
    }
}
