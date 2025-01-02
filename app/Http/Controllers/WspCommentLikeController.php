<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
