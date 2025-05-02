<?php

namespace App\Http\Controllers\Music\Comment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MusicPostComment;

class MpCommentLikeController extends Controller
{
    // いいね一覧画面の表示
    public function index($comment_id)
    {
        // 音楽感想のコメントテーブルから、今回開いているコメントにいいねしたユーザーidを取得
        $users = MusicPostComment::find($comment_id)->users()->get();
        return view('user_interactions.like_list')->with(['users' => $users]);
    }
}
