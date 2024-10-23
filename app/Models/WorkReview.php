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
        'user_id',
        'post_title',
        'body',
    ];

    // 参照させたいwork_reviewsを指定
    protected $table = 'work_reviews';

    // created_atで降順に並べたあと、limitで件数制限をかける
    public function getPaginateByLimit($work_id, int $limit_count = 5)
    {
        return $this->where('work_id', $work_id)->orderBy('created_at', 'DESC')->paginate($limit_count);
    }

    // 作品idと投稿idを指定して、投稿の詳細表示を行う
    public function getDetailPost($work_id, $post_id)
    {
        return $this->where([
            ['work_id', $work_id],
            ['id', $post_id],
        ])->first();
    }

    // 条件とその値を指定してデータを1件取得する
    public function getRestrictedPost($condition, $column_name)
    {
        return $this->where($condition, $column_name)->first();
    }

    // Workに対するリレーション 1対1の関係
    public function work()
    {
        return $this->belongsTo(Work::class);
    }

    // Userに対するリレーション 1対1の関係
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // カテゴリーに対するリレーション 多対多の関係
    public function categories()
    {
        return $this->belongsToMany(WorkReviewCategory::class);
    }
}
