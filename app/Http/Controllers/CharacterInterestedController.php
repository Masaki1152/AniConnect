<?php

namespace App\Http\Controllers;

use App\Models\Character;
use Illuminate\Http\Request;

class CharacterInterestedController extends Controller
{
    // 「気になる」登録一覧画面の表示
    public function index($character_id)
    {
        // 登場人物テーブルから、今回開いているあらすじを「気になる」登録したユーザーidを取得
        $users = Character::whereId($character_id)->first()->users()->get();
        return view('components.interested_list')->with(['users' => $users]);
    }
}
