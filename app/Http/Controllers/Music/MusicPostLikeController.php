<?php

namespace App\Http\Controllers\Music;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MusicPost;

class MusicPostLikeController extends Controller
{
    // いいねしたユーザーの表示
    public function index($music_id, $music_post_id)
    {
        // 音楽感想テーブルから、今回開いている音楽感想にいいねしたユーザーidを取得
        $users = MusicPost::whereId($music_post_id)->first()->users()->get();
        return view('like_list')->with(['users' => $users]);
    }
}
