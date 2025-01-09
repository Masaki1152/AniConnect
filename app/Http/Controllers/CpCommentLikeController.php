<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CharacterPostComment;

class CpCommentLikeController extends Controller
{
    // いいね一覧画面の表示
    public function index($comment_id)
    {
        // 登場人物感想のコメントテーブルから、今回開いているコメントにいいねしたユーザーidを取得
        $users = CharacterPostComment::find($comment_id)->users()->get();
        return view('like_list')->with(['users' => $users]);
    }
}
