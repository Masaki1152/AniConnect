<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnimePilgrimagePost;

class AnimePilgrimagePostLikeController extends Controller
{
    // いいねしたユーザーの表示
    public function index($pilgrimage_id, $pilgrimage_post_id)
    {
        // 聖地感想テーブルから、今回開いている聖地感想にいいねしたユーザーidを取得
        $users = AnimePilgrimagePost::whereId($pilgrimage_post_id)->first()->users()->get();
        return view('pilgrimage_post_likes.index')->with(['users' => $users]);
    }
}
