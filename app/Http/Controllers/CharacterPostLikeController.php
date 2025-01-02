<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CharacterPost;

class CharacterPostLikeController extends Controller
{
    // いいねしたユーザーの表示
    public function index($character_id, $character_post_id)
    {
        // 登場人物感想テーブルから、今回開いている登場人物感想にいいねしたユーザーidを取得
        $users = CharacterPost::whereId($character_post_id)->first()->users()->get();
        return view('like_list')->with(['users' => $users]);
    }
}
