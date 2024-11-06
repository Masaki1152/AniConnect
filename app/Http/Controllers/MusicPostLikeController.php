<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MusicPost;

class MusicPostLikeController extends Controller
{
    // いいねしたユーザーの表示
    public function index($music_id, $music_post_id)
    {
        // 音楽感想テーブルから、今回開いている音楽感想にいいねしたユーザーidを取得
        $users = MusicPost::whereId($music_post_id)->first()->users()->get();
        return view('music_post_likes.index')->with(['users' => $users]);
    }
}
