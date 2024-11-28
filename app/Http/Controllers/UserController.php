<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    // ユーザー画面の表示
    public function index()
    {
        $users = User::orderBy('id', 'ASC')->where(function ($query) {
            // キーワード検索がなされた場合
            if ($search = request('search')) {
                // 検索語のスペースを半角に統一
                $search_split = mb_convert_kana($search, 's');
                // 半角スペースで単語ごとに分割して配列にする
                $search_array = preg_split('/[\s]+/', $search_split);
                foreach ($search_array as $search_word) {
                    $query->where(function ($query) use ($search_word) {
                        $query->where('name', 'LIKE', "%{$search_word}%")
                            ->orWhere('introduction', 'LIKE', "%{$search_word}%");
                    });
                }
            }
        })->paginate(10);
        return view('users.index')->with(['users' => $users]);
    }

    // 詳細なユーザー情報を表示する
    public function show($user_id)
    {
        $user = User::where('id', $user_id)->first();
        return view('users.show')->with(['user' => $user]);
    }
}
