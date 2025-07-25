<?php

namespace App\Http\Controllers\AnimePilgrimage\Comment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AnimePilgrimagePostComment;

class AppCommentLikeController extends Controller
{
    // いいね一覧画面の表示
    public function index($comment_id)
    {
        // 聖地感想のコメントテーブルから、今回開いているコメントにいいねしたユーザーidを取得
        $users = AnimePilgrimagePostComment::find($comment_id)->users()->get();
        return view('components.molecules.list.like_list')->with(['users' => $users]);
    }
}
