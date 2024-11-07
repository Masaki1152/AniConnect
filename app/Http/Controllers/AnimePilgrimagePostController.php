<?php

namespace App\Http\Controllers;

use App\Http\Requests\PilgrimagePostRequest;
use App\Models\AnimePilgrimagePost;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class AnimePilgrimagePostController extends Controller
{
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
}
