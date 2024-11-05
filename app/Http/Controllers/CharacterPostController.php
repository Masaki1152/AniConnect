<?php

namespace App\Http\Controllers;

use App\Http\Requests\CharacterPostRequest;
use App\Models\CharacterPost;
use App\Models\CharacterPostCategory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class CharacterPostController extends Controller
{
    use SoftDeletes;

    // 登場人物感想投稿一覧の表示
    public function index(CharacterPostCategory $category, $character_id)
    {
        // 指定したidの登場人物の投稿のみを表示
        $character_posts = CharacterPost::where('character_id', $character_id)->orderBy('id', 'DESC')->where(function ($query) {
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

    // 感想投稿編集画面を表示する
    public function edit(CharacterPost $characterPost, CharacterPostCategory $category, $character_id, $character_post_id)
    {
        return view('character_posts.edit')->with(['character_post' => $characterPost->getDetailPost($character_id, $character_post_id), 'categories' => $category->get()]);
    }

    // 感想投稿の編集を実行する
    public function update(CharacterPostRequest $request, CharacterPost $characterPost, $character_id, $character_post_id)
    {
        $input_post = $request['character_post'];
        $input_categories = $request->character_post['categories_array'];
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
        $targetCharacterPost = $characterPost->getDetailPost($character_id, $character_post_id);
        $targetCharacterPost->fill($input_post)->save();
        // カテゴリーとの中間テーブルにデータを保存
        // 中間テーブルへの紐づけと解除を行うsyncメソッドを使用
        $targetCharacterPost->categories()->sync($input_categories);
        return redirect()->route('character_posts.show', ['character_id' => $targetCharacterPost->character_id, 'character_post_id' => $targetCharacterPost->id]);
    }

    // 感想投稿を削除する
    public function delete(CharacterPost $characterPost, $character_id, $character_post_id)
    {
        // 編集の対象となるデータを取得
        $targetCharacterPost = $characterPost->getDetailPost($character_id, $character_post_id);
        $targetCharacterPost->delete();
        return redirect()->route('character_posts.index', ['character_id' => $character_id]);
    }

    // 投稿にいいねを行う
    public function like(CharacterPost $characterPost, $character_id, $character_post_id)
    {
        // 投稿が見つからない場合の処理
        $character_post = CharacterPost::find($character_post_id);
        if (!$character_post) {
            return response()->json(['message' => 'Post not found'], 404);
        }
        // 現在ログインしているユーザーが既にいいねしていればtrueを返す
        $isLiked = $character_post->users()->where('user_id', Auth::id())->exists();
        if ($isLiked) {
            // 既にいいねしている場合
            $character_post->users()->detach(Auth::id());
            // いいねしたユーザー数の取得
            $count = count($character_post->users()->pluck('character_post_id')->toArray());
            return response()->json(['status' => 'unliked', 'like_user' => $count]);
        } else {
            // 初めてのいいねの場合
            $character_post->users()->attach(Auth::id());
            // いいねしたユーザー数の取得
            $count = count($character_post->users()->pluck('character_post_id')->toArray());
            return response()->json(['status' => 'liked', 'like_user' => $count]);
        }
        return back();
    }
}
