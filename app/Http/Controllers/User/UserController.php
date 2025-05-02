<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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
        return view('user_interactions.users.index')->with(['users' => $users, 'auth_user_id' => $auth_user_id]);
    }

    // 詳細なユーザー情報を表示する
    public function show($user_id)
    {
        $user = User::where('id', $user_id)->first();
        return view('user_interactions.users.show')->with(['user' => $user]);
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

    public function fetchPosts(Request $request, User $user, $user_id, $type)
    {
        // 検索キーワードがあれば取得
        $keyword = $request->input('keyword', '');
        // 投稿、コメント、いいねの種類を取得
        $switchType = $request->input('switchType');

        // 各switchTypeに対応するメソッドとビューをマッピング
        $actions = [
            'impressions' => [
                'method' => fn() => $user->fetchPosts($user_id, $type, $keyword),
                'view'   => 'components.molecules.cell.post-cell'
            ],
            'comments' => [
                'method' => fn() => $user->fetchComments($user_id, $type, $keyword),
                'view'   => 'components.molecules.cell.comment-cell'
            ],
            'likes' => [
                'method' => fn() => $user->fetchLikePosts($user_id, $type, $keyword),
                'view'   => 'components.molecules.cell.post-cell'
            ]
        ];

        // 投稿を取得する
        $posts = $actions[$switchType]['method']();

        return view($actions[$switchType]['view'], [
            'posts' => $posts,
            'currentPage' => $posts->currentPage(),
            'lastPage' => $posts->lastPage()
        ]);
    }
}
