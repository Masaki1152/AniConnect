<?php

namespace App\Http\Controllers\AnimePilgrimage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AnimePilgrimagePost;

class AnimePilgrimagePostLikeController extends Controller
{
    // いいねしたユーザーの表示
    public function index($pilgrimage_id, $pilgrimage_post_id)
    {
        // 聖地感想テーブルから、今回開いている聖地感想にいいねしたユーザーidを取得
        $users = AnimePilgrimagePost::whereId($pilgrimage_post_id)->first()->users()->get();
        return view('components.molecules.list.like_list')->with(['users' => $users]);
    }
}
