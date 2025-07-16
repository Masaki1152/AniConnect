<?php

namespace App\Http\Controllers\Notification\Comment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NotificationComment;

class NotificationCommentLikeController extends Controller
{
    // いいね一覧画面の表示
    public function index($comment_id)
    {
        // お知らせのコメントテーブルから、今回開いているコメントにいいねしたユーザーidを取得
        $users = NotificationComment::find($comment_id)->users()->get();
        return view('components.molecules.list.like_list')->with(['users' => $users]);
    }
}
