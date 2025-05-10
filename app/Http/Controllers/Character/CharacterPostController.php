<?php

namespace App\Http\Controllers\Character;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CharacterPostRequest;
use App\Models\Character;
use App\Models\CharacterPost;
use App\Models\CharacterPostCategory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Traits\CommonFunction;

class CharacterPostController extends Controller
{
    use SoftDeletes;
    use CommonFunction;

    // 登場人物感想投稿一覧の表示
    public function index(Request $request, CharacterPost $characterPost, CharacterPostCategory $category, $character_id)
    {
        // クリックされたカテゴリーidを取得
        $categoryIds = $request->filled('checkedCategories')
            ? ($request->input('checkedCategories'))
            : [];
        // 検索キーワードがあれば取得
        $search = $request->input('search', '');
        // キーワードに部分一致する投稿を取得
        $character_posts = $characterPost->fetchCharacterPosts($character_id, $search, $categoryIds);
        // 検索結果の件数を取得
        $totalResults = $character_posts->total();
        // 単体のオブジェクトを取得
        $character_first = CharacterPost::where('character_id', $character_id)->first();
        // 登場人物のオブジェクトを取得
        $character = Character::find($character_id);

        // カテゴリー検索で選択されたカテゴリーをまとめる
        $selectedCategories = [];
        // カテゴリーの情報を取得する
        foreach ($categoryIds as $categoryId) {
            $category = CharacterPostCategory::find($categoryId);
            array_push($selectedCategories, $category->name);
        }

        return view('posts.character_posts.index')->with([
            'character_posts' => $character_posts,
            'character_first' => $character_first,
            'character' => $character,
            'categories' => $category->get(),
            'totalResults' => $totalResults,
            'search' => $search,
            'selectedCategories' => $selectedCategories
        ]);
    }

    // 登場人物感想投稿詳細の表示
    public function show(CharacterPost $characterPost, CharacterPostCategory $category, $character_id, $character_post_id)
    {
        // 登場人物のオブジェクトを取得
        $character = Character::find($character_id);
        return view('posts.character_posts.show')->with(['character_post' => $characterPost->getDetailPost($character_id, $character_post_id), 'character' => $character, 'categories' => $category->get()]);
    }

    // 新規投稿作成画面を表示する
    public function create(CharacterPost $characterPost, CharacterPostCategory $category, $character_id)
    {
        // 登場人物のオブジェクトを取得
        $character = Character::find($character_id);
        return view('posts.character_posts.create')->with(['character_post' => $characterPost->getRestrictedPost('character_id', $character_id), 'character' => $character, 'categories' => $category->get()]);
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
                $image_url = Cloudinary::upload($image->getRealPath(), [
                    'transformation' => [
                        'width' => 800,
                        'height' => 600,
                        'crop' => 'pad',
                        'background' => 'white',
                    ]
                ])->getSecurePath();
                $input_post += ["image$counter" => $image_url];
                $counter++;
            }
        }
        // ログインしているユーザーidの登録
        $input_post['user_id'] = Auth::id();
        $characterPost->fill($input_post)->save();
        // カテゴリーとの中間テーブルにデータを保存
        $characterPost->categories()->attach($input_categories);
        $message = __('messages.new_post_created');
        return redirect()->route('character_posts.show', ['character_id' => $characterPost->character_id, 'character_post_id' => $characterPost->id])->with('message', $message);
    }

    // 感想投稿編集画面を表示する
    public function edit(CharacterPost $characterPost, CharacterPostCategory $category, $character_id, $character_post_id)
    {
        return view('posts.character_posts.edit')->with(['character_post' => $characterPost->getDetailPost($character_id, $character_post_id), 'categories' => $category->get()]);
    }

    // 感想投稿の編集を実行する
    public function update(CharacterPostRequest $request, CharacterPost $characterPost, $character_id, $character_post_id)
    {
        $input_post = $request['character_post'];
        $input_categories = $request->character_post['categories_array'];
        // 保存する画像のPathの配列
        $image_paths = [];
        // 削除されていない既存画像がある場合のみ以下の処理を実行
        if ($request['remainedImages'][0]) {
            // JSON文字列をデコードしてPHP配列に変換
            $remained_images = json_decode($request['remainedImages'][0], true);
            // Pathの配列に削除されていない画像のPathを追加
            foreach ($remained_images as $remained_image) {
                array_push($image_paths, $remained_image['url']);
            }
        }
        // 削除された既存画像がある場合のみ以下の処理を実行
        if ($request['removedImages'][0]) {
            // JSON文字列をデコードしてPHP配列に変換
            $removed_images = json_decode($request['removedImages'][0], true);
            // 削除された画像のPathをCloudinaryから削除
            foreach ($removed_images as $removed_image) {
                // Cloudinaryに登録した画像のURLからpublic_idを取得する
                $public_id = $this->extractPublicIdFromUrl($removed_image['url']);
                Cloudinary::destroy($public_id);
            }
        }
        //cloudinaryへ画像を送信し、画像のURLを$image_urlに代入
        //画像ファイルが送られた時だけ処理が実行される
        if ($request->file('images')) {
            foreach ($request->file('images') as $image) {
                $image_path = Cloudinary::upload($image->getRealPath(), [
                    'transformation' => [
                        'width' => 800,
                        'height' => 600,
                        'crop' => 'pad',
                        'background' => 'white',
                    ]
                ])->getSecurePath();
                array_push($image_paths, $image_path);
            }
        }
        // $imagePathのうち、Pathのないものにはnullを代入
        $vacantElementNum = 4 - count($image_paths);
        for ($counter = 0; $counter < $vacantElementNum; $counter++) {
            array_push($image_paths, NULL);
        }
        $counter = 1;
        // 今回保存するPathをDBのImageカラムに代入する
        foreach ($image_paths as $imagePath) {
            $input_post["image$counter"] = $imagePath;
            $counter++;
        }
        // 編集の対象となるデータを取得
        $targetCharacterPost = $characterPost->getDetailPost($character_id, $character_post_id);
        $targetCharacterPost->fill($input_post)->save();
        // カテゴリーとの中間テーブルにデータを保存
        // 中間テーブルへの紐づけと解除を行うsyncメソッドを使用
        $targetCharacterPost->categories()->sync($input_categories);
        $message = __('messages.post_edited');
        return redirect()->route('character_posts.show', ['character_id' => $targetCharacterPost->character_id, 'character_post_id' => $targetCharacterPost->id])->with('message', $message);
    }

    // 感想投稿を削除する
    public function delete(CharacterPost $characterPost, $character_id, $character_post_id)
    {
        // 編集の対象となるデータを取得
        $targetCharacterPost = $characterPost->getDetailPost($character_id, $character_post_id);
        // 削除する投稿の画像も削除する処理
        for ($counter = 1; $counter < 5; $counter++) {
            $removed_image_path = $targetCharacterPost->{'image' . $counter};
            // DBのimageの中身がnullであれば処理をスキップする
            if (is_null($removed_image_path)) {
                break;
            }
            $public_id = $this->extractPublicIdFromUrl($removed_image_path);
            Cloudinary::destroy($public_id);
        }

        // 投稿へのコメントの画像も削除する処理
        $comments = $targetCharacterPost->characterPostComments;
        foreach ($comments as $comment) {
            for ($counter = 1; $counter < 5; $counter++) {
                $comment_image_path = $comment->{'image' . $counter};
                if (is_null($comment_image_path)) {
                    continue;
                }
                $public_id = $this->extractPublicIdFromUrl($comment_image_path);
                Cloudinary::destroy($public_id);
            }
            // コメント自体も削除
            $comment->delete();
        }
        // データの削除
        $targetCharacterPost->delete();
        $message = __('messages.post_deleted');
        return redirect()->route('character_posts.index', ['character_id' => $character_id])->with('message', $message);
    }

    // 投稿にいいねを行う
    public function like($character_id, $character_post_id)
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
            $status = 'unliked';
            $message = __('messages.unliked');;
        } else {
            // 初めてのいいねの場合
            $character_post->users()->attach(Auth::id());
            $status = 'liked';
            $message = __('messages.liked');;
        }
        // いいねしたユーザー数の取得
        $count = count($character_post->users()->pluck('character_post_id')->toArray());
        return response()->json(['status' => $status, 'like_user' => $count, 'message' => $message]);
    }
}
