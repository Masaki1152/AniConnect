<?php

namespace App\Http\Controllers\WorkStory\Comment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\WorkStoryPostComment;

class WspCommentLikeController extends Controller
{
    // いいね一覧画面の表示
    public function index($comment_id)
    {
        // あらすじ感想のコメントテーブルから、今回開いているコメントにいいねしたユーザーidを取得
        $users = WorkStoryPostComment::find($comment_id)->users()->get();
        return view('like_list')->with(['users' => $users]);
    }
}
