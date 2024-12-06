<?php

namespace App\Http\Controllers;

use App\Http\Requests\MusicPostRequest;
use App\Models\MusicPost;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class MusicPostController extends Controller
{
    use SoftDeletes;

    // 音楽感想投稿一覧の表示
    public function index($music_id)
    {
        // 指定したidの音楽の投稿のみを表示
        $music_posts = MusicPost::where('music_id', $music_id)->orderBy('id', 'DESC')->where(function ($query) {
            // キーワード検索がなされた場合
            if ($search = request('search')) {
                // 検索語のスペースを半角に統一
                $search_split = mb_convert_kana($search, 's');
                // 半角スペースで単語ごとに分割して配列にする
                $search_array = preg_split('/[\s]+/', $search_split);
                foreach ($search_array as $search_word) {
                    $query->where(function ($query) use ($search_word) {
                        $query->where('post_title', 'LIKE', "%{$search_word}%")
                            ->orWhere('body', 'LIKE', "%{$search_word}%");
                    });
                }
            }
        })->paginate(5);
        $music_first = MusicPost::where('music_id', $music_id)->first();
        return view('music_posts.index')->with(['music_posts' => $music_posts, 'music_first' => $music_first]);
    }

    // 音楽感想投稿詳細の表示
    public function show(MusicPost $musicPost, $music_id, $music_post_id)
    {
        return view('music_posts.show')->with(['music_post' => $musicPost->getDetailPost($music_id, $music_post_id)]);
    }

    // 新規投稿作成画面を表示する
    public function create(MusicPost $musicPost, $music_id)
    {
        return view('music_posts.create')->with(['music_post' => $musicPost->getRestrictedPost('music_id', $music_id)]);
    }

    // 新しく記述した内容を保存する
    public function store(MusicPost $musicPost, MusicPostRequest $request)
    {
        $input_post = $request['music_post'];
        // ログインしているユーザーidの登録
        $input_post['user_id'] = Auth::id();
        $musicPost->fill($input_post)->save();
        return redirect()->route('music_posts.show', ['music_id' => $musicPost->music_id, 'music_post_id' => $musicPost->id]);
    }

    // 感想投稿編集画面を表示する
    public function edit(MusicPost $musicPost, $music_id, $music_post_id)
    {
        return view('music_posts.edit')->with(['music_post' => $musicPost->getDetailPost($music_id, $music_post_id)]);
    }

    // 感想投稿の編集を実行する
    public function update(MusicPostRequest $request, MusicPost $musicPost, $music_id, $music_post_id)
    {
        $input_post = $request['music_post'];
        // 編集の対象となるデータを取得
        $targetMusicPost = $musicPost->getDetailPost($music_id, $music_post_id);
        $targetMusicPost->fill($input_post)->save();
        return redirect()->route('music_posts.show', ['music_id' => $targetMusicPost->music_id, 'music_post_id' => $targetMusicPost->id]);
    }

    // 感想投稿を削除する
    public function delete(MusicPost $musicPost, $music_id, $music_post_id)
    {
        // 編集の対象となるデータを取得
        $targetMusicPost = $musicPost->getDetailPost($music_id, $music_post_id);
        $targetMusicPost->delete();
        return redirect()->route('music_posts.index', ['music_id' => $music_id]);
    }

    // 投稿にいいねを行う
    public function like($music_id, $music_post_id)
    {
        // 投稿が見つからない場合の処理
        $music_post = MusicPost::find($music_post_id);
        if (!$music_post) {
            return response()->json(['message' => 'Post not found'], 404);
        }
        // 現在ログインしているユーザーが既にいいねしていればtrueを返す
        $isLiked = $music_post->users()->where('user_id', Auth::id())->exists();
        if ($isLiked) {
            // 既にいいねしている場合
            $music_post->users()->detach(Auth::id());
            $status = 'unliked';
            $message = 'いいねを解除しました';
        } else {
            // 初めてのいいねの場合
            $music_post->users()->attach(Auth::id());
            $status = 'liked';
            $message = 'いいねしました';
        }
        // いいねしたユーザー数の取得
        $count = count($music_post->users()->pluck('music_post_id')->toArray());
        return response()->json(['status' => $status, 'like_user' => $count, 'message' => $message]);
    }
}
