<?php

namespace App\Http\Controllers\Work;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\WorkPost;

class WorkPostLikeController extends Controller
{
    // いいね一覧画面の表示
    public function index(WorkPost $workPost, $work_id, $work_post_id)
    {
        // 作品感想テーブルから、今回開いている作品感想にいいねしたユーザーidを取得
        $users = WorkPost::whereId($work_post_id)->first()->users()->get();
        return view('components.molecules.list.like_list')->with(['users' => $users]);
    }
}
