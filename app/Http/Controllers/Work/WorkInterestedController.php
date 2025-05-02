<?php

namespace App\Http\Controllers\Work;

use App\Http\Controllers\Controller;
use App\Models\Work;
use Illuminate\Http\Request;

class WorkInterestedController extends Controller
{
    // 「気になる」登録一覧画面の表示
    public function index($work_id)
    {
        // 作品テーブルから、今回開いている作品を「気になる」登録したユーザーidを取得
        $users = Work::whereId($work_id)->first()->users()->get();
        return view('components.molecules.list.interested_list')->with(['users' => $users]);
    }
}
