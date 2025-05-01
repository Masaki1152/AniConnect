<?php

namespace App\Http\Controllers\Music;

use App\Http\Controllers\Controller;
use App\Models\Music;
use Illuminate\Http\Request;

class MusicInterestedController extends Controller
{
    // 「気になる」登録一覧画面の表示
    public function index($music_id)
    {
        // 音楽テーブルから、今回開いているあらすじを「気になる」登録したユーザーidを取得
        $users = Music::whereId($music_id)->first()->users()->get();
        return view('components.interested_list')->with(['users' => $users]);
    }
}
