<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\WorkReview;
use App\Models\WorkStoryPost;
use App\Models\CharacterPost;
use App\Models\MusicPost;
use App\Models\AnimePilgrimagePost;

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
        // ログインしているユーザー
        $auth_user_id = Auth::id();
        return view('users.index')->with(['users' => $users, 'auth_user_id' => $auth_user_id]);
    }

    // 詳細なユーザー情報を表示する
    public function show($user_id)
    {
        $user = User::where('id', $user_id)->first();
        return view('users.show')->with(['user' => $user]);
    }

    // ユーザーのフォロー行う
    public function follow($user_id)
    {
        // ログインしているユーザーの取得
        $auth_user = Auth::user();
        // フォロー対象のユーザーを取得
        $user = User::find($user_id);
        // ユーザーが見つからない場合の処理
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        // 現在ログインしているユーザーが既にフォローしていればtrueを返す
        $isFollowing = $auth_user->followings()->where('followed_id', $user->id)->exists();
        if ($isFollowing) {
            // 既にフォローしている場合、フォローの解除
            $auth_user->followings()->detach($user->id);
            $status = 'unfollowed';
        } else {
            // 初めてのフォローの場合
            $auth_user->followings()->attach($user->id);
            $status = 'followed';
        }

        // 最新のフォロワー・フォロー数を取得
        $followingCount = $user->followings()->count();
        $followersCount = $user->followers()->count();
        // ログインしているユーザーの最新のフォロワー・フォロー数を取得
        $authFollowingCount = $auth_user->followings()->count();
        $authFollowersCount = $auth_user->followers()->count();

        return response()->json([
            'status' => $status,
            'followingCount' => $followingCount,
            'followersCount' => $followersCount,
            'authFollowingCount' => $authFollowingCount,
            'authFollowersCount' => $authFollowersCount
        ]);
    }

    public function fetchPosts($user_id, $type)
    {
        $posts = [];

        // 必要な種類の投稿を取得
        // 合わせて投稿者情報をリレーションで取得
        switch ($type) {
            case 'work':
                $posts = WorkReview::where('user_id', $user_id)->with(['user', 'work'])->get();
                break;
            case 'workStory':
                $posts = WorkStoryPost::where('user_id', $user_id)->with(['user', 'work', 'workStory'])->get();
                break;
            case 'character':
                $posts = CharacterPost::where('user_id', $user_id)->with(['user', 'character'])->get();
                break;
            case 'music':
                $posts = MusicPost::where('user_id', $user_id)->with(['user', 'music'])->get();
                break;
            case 'animePilgrimage':
                $posts = AnimePilgrimagePost::where('user_id', $user_id)->with(['user', 'animePilgrimage'])->get();
                break;
        }

        return response()->json($posts);
    }
}
