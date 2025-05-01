<?php

namespace App\Http\Controllers\AnimePilgrimage;

use App\Http\Controllers\Controller;
use App\Models\AnimePilgrimage;
use Illuminate\Http\Request;

class AnimePilgrimageInterestedController extends Controller
{
    // 「気になる」登録一覧画面の表示
    public function index($pilgrimage_id)
    {
        // 聖地テーブルから、今回開いているあらすじを「気になる」登録したユーザーidを取得
        $users = AnimePilgrimage::whereId($pilgrimage_id)->first()->users()->get();
        return view('components.interested_list')->with(['users' => $users]);
    }
}
