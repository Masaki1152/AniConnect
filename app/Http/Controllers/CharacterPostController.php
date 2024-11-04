<?php

namespace App\Http\Controllers;

use App\Http\Requests\CharacterPostRequest;
use App\Models\CharacterPost;
use App\Models\CharacterPostCategory;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class CharacterPostController extends Controller
{
    // 登場人物感想投稿一覧の表示
    public function index(CharacterPostCategory $category, $character_id)
    {
        // 指定したidの登場人物の投稿のみを表示
        $character_posts = CharacterPost::where('character_id', $character_id)->orderBy('id', 'ASC')->where(function ($query) {
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
        $character_first = CharacterPost::where('character_id', $character_id)->first();
        return view('character_posts.index')->with(['character_posts' => $character_posts, 'character_first' => $character_first, 'categories' => $category->get()]);
    }

    // 登場人物感想投稿詳細の表示
    public function show(CharacterPost $characterPost, CharacterPostCategory $category, $character_id, $character_post_id)
    {
        return view('character_posts.show')->with(['character_post' => $characterPost->getDetailPost($character_id, $character_post_id), 'categories' => $category->get()]);
    }

    // 新規投稿作成画面を表示する
    public function create(CharacterPost $characterPost, CharacterPostCategory $category, $character_id)
    {
        return view('character_posts.create')->with(['character_post' => $characterPost->getRestrictedPost('character_id', $character_id), 'categories' => $category->get()]);
    }

    // 新しく記述した内容を保存する
    public function store(CharacterPost $characterPost, CharacterPostRequest $request)
    {
        $input_post = $request['character_post'];
        $input_categories = $request->character_post['categories_array'];
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
        $characterPost->fill($input_post)->save();
        // カテゴリーとの中間テーブルにデータを保存
        $characterPost->categories()->attach($input_categories);
        return redirect()->route('character_posts.show', ['character_id' => $characterPost->character_id, 'character_post_id' => $characterPost->id]);
    }
}
