<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\MusicPostRequest;
use App\Models\Music;
use App\Models\MusicPost;
use App\Models\MusicPostCategory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class MusicPostController extends Controller
{
    use SoftDeletes;

    // 音楽感想投稿一覧の表示
    public function index(Request $request, MusicPost $musicPost, MusicPostCategory $category, $music_id)
    {
        // クリックされたカテゴリーidを取得
        $categoryIds = $request->filled('checkedCategories')
            ? ($request->input('checkedCategories'))
            : [];
        // 検索キーワードがあれば取得
        $search = $request->input('search', '');
        // キーワードに部分一致する投稿を取得
        $music_posts = $musicPost->fetchMusicPosts($music_id, $search, $categoryIds);
        // 検索結果の件数を取得
        $totalResults = $music_posts->total();
        // 単体のオブジェクトを取得
        $music_first = MusicPost::where('music_id', $music_id)->first();
        // 音楽のオブジェクトを取得
        $music = Music::find($music_id);

        // カテゴリー検索で選択されたカテゴリーをまとめる
        $selectedCategories = [];
        // カテゴリーの情報を取得する
        foreach ($categoryIds as $categoryId) {
            $category = MusicPostCategory::find($categoryId);
            array_push($selectedCategories, $category->name);
        }

        return view('music_posts.index')->with([
            'music_posts' => $music_posts,
            'music_first' => $music_first,
            'music' => $music,
            'categories' => $category->get(),
            'totalResults' => $totalResults,
            'search' => $search,
            'selectedCategories' => $selectedCategories
        ]);
    }

    // 音楽感想投稿詳細の表示
    public function show(MusicPost $musicPost, MusicPostCategory $category, $music_id, $music_post_id)
    {
        return view('music_posts.show')->with(['music_post' => $musicPost->getDetailPost($music_id, $music_post_id), 'categories' => $category->get()]);
    }

    // 新規投稿作成画面を表示する
    public function create(MusicPost $musicPost, MusicPostCategory $category, $music_id)
    {
        // 音楽のオブジェクトを取得
        $music = Music::find($music_id);
        return view('music_posts.create')->with(['music_post' => $musicPost->getRestrictedPost('music_id', $music_id), 'music' => $music, 'categories' => $category->get()]);
    }

    // 新しく記述した内容を保存する
    public function store(MusicPost $musicPost, MusicPostRequest $request)
    {
        $input_post = $request['music_post'];
        $input_categories = $request->music_post['categories_array'];
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
        $musicPost->fill($input_post)->save();
        // カテゴリーとの中間テーブルにデータを保存
        $musicPost->categories()->attach($input_categories);
        return redirect()->route('music_posts.show', ['music_id' => $musicPost->music_id, 'music_post_id' => $musicPost->id])->with('message', '新しい投稿を作成しました');
    }

    // 感想投稿編集画面を表示する
    public function edit(MusicPost $musicPost, MusicPostCategory $category, $music_id, $music_post_id)
    {
        return view('music_posts.edit')->with(['music_post' => $musicPost->getDetailPost($music_id, $music_post_id), 'categories' => $category->get()]);
    }

    // 感想投稿の編集を実行する
    public function update(MusicPostRequest $request, MusicPost $musicPost, $music_id, $music_post_id)
    {
        $input_post = $request['music_post'];
        $input_categories = $request->music_post['categories_array'];
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
        $targetMusicPost = $musicPost->getDetailPost($music_id, $music_post_id);
        $targetMusicPost->fill($input_post)->save();
        // カテゴリーとの中間テーブルにデータを保存
        // 中間テーブルへの紐づけと解除を行うsyncメソッドを使用
        $targetMusicPost->categories()->sync($input_categories);
        return redirect()->route('music_posts.show', ['music_id' => $targetMusicPost->music_id, 'music_post_id' => $targetMusicPost->id])->with('message', '投稿を編集しました');
    }

    // 感想投稿を削除する
    public function delete(MusicPost $musicPost, $music_id, $music_post_id)
    {
        // 編集の対象となるデータを取得
        $targetMusicPost = $musicPost->getDetailPost($music_id, $music_post_id);
        // 削除する投稿の画像も削除する処理
        for ($counter = 1; $counter < 5; $counter++) {
            $removed_image_path = $targetMusicPost->{'image' . $counter};
            // DBのimageの中身がnullであれば処理をスキップする
            if (is_null($removed_image_path)) {
                break;
            }
            $public_id = $this->extractPublicIdFromUrl($removed_image_path);
            Cloudinary::destroy($public_id);
        }
        $targetMusicPost->delete();
        return redirect()->route('music_posts.index', ['music_id' => $music_id])->with('message', '投稿を削除しました');
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

    // Cloudinaryにある画像のURLからpublic_Idを取得する
    public function extractPublicIdFromUrl($url)
    {
        // URLの中からpublic_idを抽出するための正規表現
        $pattern = '/upload\/(?:v\d+\/)?([^\.]+)\./';

        if (preg_match($pattern, $url, $matches)) {
            // 抽出されたpublic_id
            return $matches[1];
        }
        // 該当しない場合はnull
        return null;
    }
}
