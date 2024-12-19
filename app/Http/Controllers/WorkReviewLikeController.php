<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkReview;

class WorkReviewLikeController extends Controller
{
    // いいね一覧画面の表示
    public function index(WorkReview $workreview, $work_id, $work_review_id)
    {
        // 作品感想テーブルから、今回開いている作品感想にいいねしたユーザーidを取得
        $users = WorkReview::whereId($work_review_id)->first()->users()->get();
        return view('work_review_likes.index')->with(['users' => $users]);
    }
}
