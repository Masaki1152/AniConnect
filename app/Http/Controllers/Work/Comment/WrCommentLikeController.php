<?php

namespace App\Http\Controllers\Work\Comment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\WorkReviewComment;

class WrCommentLikeController extends Controller
{
    // いいね一覧画面の表示
    public function index($comment_id)
    {
        // 作品感想のコメントテーブルから、今回開いているコメントにいいねしたユーザーidを取得
        $users = WorkReviewComment::whereId($comment_id)->first()->users()->get();
        return view('components.molecules.list.like_list')->with(['users' => $users]);
    }
}
