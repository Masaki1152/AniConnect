<?php

namespace App\Http\Controllers;

use App\Models\WorkStory;
use Illuminate\Http\Request;

class WorkStoryInterestedController extends Controller
{
    // 「気になる」登録一覧画面の表示
    public function index($work_id, $work_story_id)
    {
        // あらすじテーブルから、今回開いているあらすじを「気になる」登録したユーザーidを取得
        $users = WorkStory::whereId($work_story_id)->first()->users()->get();
        return view('components.interested_list')->with(['users' => $users]);
    }
}
