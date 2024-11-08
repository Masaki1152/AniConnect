<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkStoryPost extends Model
{
    use HasFactory;

    // 参照させたいwork_story_postsを指定
    protected $table = 'work_story_posts';

    // あらすじidと投稿idを指定して、投稿の詳細表示を行う
    public function getDetailPost($work_story_id, $work_story_post_id)
    {
        return $this->where([
            ['sub_title_id', $work_story_id],
            ['id', $work_story_post_id],
        ])->first();
    }

    // Work_Storyに対するリレーション 1対1の関係
    public function workStory()
    {
        return $this->belongsTo(WorkStory::class, 'sub_title_id', 'id');
    }

    // Workに対するリレーション 1対1の関係
    public function work()
    {
        return $this->belongsTo(Work::class, 'work_id', 'id');
    }

    // Userに対するリレーション 1対1の関係
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
