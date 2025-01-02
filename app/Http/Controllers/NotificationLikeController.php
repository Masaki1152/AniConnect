<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationLikeController extends Controller
{
    // お知らせへのいいね一覧の表示
    public function index($notification_id)
    {
        // お知らせテーブルから、今回開いているお知らせにいいねしたユーザーidを取得
        $users = Notification::whereId($notification_id)->first()->users()->get();
        return view('like_list')->with(['users' => $users]);
    }
}
