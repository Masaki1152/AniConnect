<?php

namespace App\Http\Controllers;

use App\Http\Requests\PilgrimagePostRequest;
use App\Models\AnimePilgrimagePost;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class AnimePilgrimagePostController extends Controller
{
    use SoftDeletes;

    // 聖地感想投稿一覧の表示
    public function index($pilgrimage_id)
    {
        // 指定したidの聖地の投稿のみを表示
        $pilgrimage_posts = AnimePilgrimagePost::where('anime_pilgrimage_id', $pilgrimage_id)->orderBy('id', 'DESC')->where(function ($query) {
            // キーワード検索がなされた場合
            if ($search = request('search')) {
                // 検索語のスペースを半角に統一
                $search_split = mb_convert_kana($search, 's');
                // 半角スペースで単語ごとに分割して配列にする
                $search_array = preg_split('/[\s]+/', $search_split);
                foreach ($search_array as $search_word) {
                    $query->where(function ($query) use ($search_word) {
                        $query->where('title', 'LIKE', "%{$search_word}%")
                            ->orwhere('scene', 'LIKE', "%{$search_word}%")
                            ->orWhere('body', 'LIKE', "%{$search_word}%");
                    });
                }
            }
        })->paginate(5);
        $pilgrimage_first = AnimePilgrimagePost::where('anime_pilgrimage_id', $pilgrimage_id)->first();
        return view('anime_pilgrimage_posts.index')->with(['pilgrimage_posts' => $pilgrimage_posts, 'pilgrimage_first' => $pilgrimage_first]);
    }

    // 聖地感想投稿詳細の表示
    public function show(AnimePilgrimagePost $pilgrimagePost, $pilgrimage_id, $pilgrimage_post_id)
    {
        return view('anime_pilgrimage_posts.show')->with(['pilgrimage_post' => $pilgrimagePost->getDetailPost($pilgrimage_id, $pilgrimage_post_id)]);
    }

    // 新規投稿作成画面を表示する
    public function create(AnimePilgrimagePost $pilgrimagePost, $pilgrimage_id)
    {
        return view('anime_pilgrimage_posts.create')->with(['pilgrimage_post' => $pilgrimagePost->getRestrictedPost('anime_pilgrimage_id', $pilgrimage_id)]);
    }

    // 新しく記述した内容を保存する
    public function store(AnimePilgrimagePost $pilgrimagePost, PilgrimagePostRequest $request)
    {
        $input_post = $request['pilgrimage_post'];
        //cloudinaryへ画像を送信し、画像のURLを$image_urlに代入
        //画像ファイルが送られた時だけ処理が実行される
        if ($request->file('images')) {
            $counter = 1;
            foreach ($request->file('images') as $image) {
                $image_url = Cloudinary::upload($image->getRealPath())->getSecurePath();
                $input_post += ["image$counter" => $image_url];
                $counter++;
            }
        }
        // ログインしているユーザーidの登録
        $input_post['user_id'] = Auth::id();
        $pilgrimagePost->fill($input_post)->save();
        return redirect()->route('pilgrimage_posts.show', ['pilgrimage_id' => $pilgrimagePost->anime_pilgrimage_id, 'pilgrimage_post_id' => $pilgrimagePost->id]);
    }

    // 感想投稿編集画面を表示する
    public function edit(AnimePilgrimagePost $pilgrimagePost, $pilgrimage_id, $pilgrimage_post_id)
    {
        return view('anime_pilgrimage_posts.edit')->with(['pilgrimage_post' => $pilgrimagePost->getDetailPost($pilgrimage_id, $pilgrimage_post_id)]);
    }

    // 感想投稿の編集を実行する
    public function update(PilgrimagePostRequest $request, AnimePilgrimagePost $pilgrimagePost, $pilgrimage_id, $pilgrimage_post_id)
    {
        $input_post = $request['pilgrimage_post'];
        //cloudinaryへ画像を送信し、画像のURLを$image_urlに代入
        //画像ファイルが送られた時だけ処理が実行される
        if ($request->file('images')) {
            $counter = 1;
            foreach ($request->file('images') as $image) {
                $image_url = Cloudinary::upload($image->getRealPath())->getSecurePath();
                $input_post["image$counter"] = $image_url;
                $counter++;
            }
        }
        // 編集の対象となるデータを取得
        $targetPilgrimagePost = $pilgrimagePost->getDetailPost($pilgrimage_id, $pilgrimage_post_id);
        $targetPilgrimagePost->fill($input_post)->save();
        return redirect()->route('pilgrimage_posts.show', ['pilgrimage_id' => $targetPilgrimagePost->anime_pilgrimage_id, 'pilgrimage_post_id' => $targetPilgrimagePost->id]);
    }

    // 感想投稿を削除する
    public function delete(AnimePilgrimagePost $pilgrimagePost, $pilgrimage_id, $pilgrimage_post_id)
    {
        // 編集の対象となるデータを取得
        $targetPilgrimagePost = $pilgrimagePost->getDetailPost($pilgrimage_id, $pilgrimage_post_id);
        $targetPilgrimagePost->delete();
        return redirect()->route('pilgrimage_posts.index', ['pilgrimage_id' => $pilgrimage_id]);
    }

    // 投稿にいいねを行う
    public function like($pilgrimage_id, $pilgrimage_post_id)
    {
        // 投稿が見つからない場合の処理
        $pilgrimage_post = AnimePilgrimagePost::find($pilgrimage_post_id);
        if (!$pilgrimage_post) {
            return response()->json(['message' => 'Post not found'], 404);
        }
        // 現在ログインしているユーザーが既にいいねしていればtrueを返す
        $isLiked = $pilgrimage_post->users()->where('user_id', Auth::id())->exists();
        if ($isLiked) {
            // 既にいいねしている場合
            $pilgrimage_post->users()->detach(Auth::id());
            // いいねしたユーザー数の取得
            $count = count($pilgrimage_post->users()->pluck('anime_pilgrimage_post_id')->toArray());
            return response()->json(['status' => 'unliked', 'like_user' => $count]);
        } else {
            // 初めてのいいねの場合
            $pilgrimage_post->users()->attach(Auth::id());
            // いいねしたユーザー数の取得
            $count = count($pilgrimage_post->users()->pluck('anime_pilgrimage_post_id')->toArray());
            return response()->json(['status' => 'liked', 'like_user' => $count]);
        }
        return back();
    }
}
