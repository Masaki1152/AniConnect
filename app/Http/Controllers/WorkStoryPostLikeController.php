<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkStoryPost;

class WorkStoryPostLikeController extends Controller
{
    // いいねしたユーザーの表示
    public function index($work_id, $work_story_id, $work_story_post_id)
    {
        // あらすじ感想テーブルから、今回開いている登場人物感想にいいねしたユーザーidを取得
        $users = WorkStoryPost::whereId($work_story_post_id)->first()->users()->get();
        return view('work_story_post_likes.index')->with(['users' => $users]);
    }
}
