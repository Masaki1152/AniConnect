<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserFollowController extends Controller
{
    // フォローしたユーザーの表示
    public function index($user_id)
    {
        // ユーザーテーブルから、対象ユーザーをフォローしたユーザーidを取得
        $users = User::whereId($user_id)->first()->followings()->get();
        // ログインしているユーザー
        $auth_user_id = Auth::id();
        return view('user_follows.index')->with(['users' => $users, 'auth_user_id' => $auth_user_id]);
    }
}
