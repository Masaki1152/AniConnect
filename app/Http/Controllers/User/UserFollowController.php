<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserFollowController extends Controller
{
    // フォローしたユーザーの表示
    public function indexFollowingUser($user_id)
    {
        // ユーザーテーブルから、対象ユーザーをフォローしたユーザーidを取得
        $users = User::whereId($user_id)->first()->followings()->get();
        // 対象のユーザー
        $selected_user = User::find($user_id);
        return view('components.molecules.list.follow_list')->with(['users' => $users, 'selected_user' => $selected_user, 'type' => 'followings']);
    }

    // フォロワーの表示
    public function indexFollowedUser($user_id)
    {
        // ユーザーテーブルから、対象ユーザーをフォローしたユーザーidを取得
        $users = User::whereId($user_id)->first()->followers()->get();
        // 対象のユーザー
        $selected_user = User::find($user_id);
        return view('components.molecules.list.follow_list')->with(['users' => $users, 'selected_user' => $selected_user, 'type' => 'followers']);
    }
}
