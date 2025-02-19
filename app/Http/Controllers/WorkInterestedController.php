<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WorkInterestedController extends Controller
{
    // 「気になる」登録一覧画面の表示
    public function index($work_id)
    {
        // 作品テーブルから、今回開いている作品を「気になる」登録したユーザーidを取得
        $users = Work::whereId($work_id)->first()->users()->get();
        return view('interested_list')->with(['users' => $users]);
    }
}
