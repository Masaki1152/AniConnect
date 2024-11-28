<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkStoryPostRequest;
use App\Models\WorkStory;
use App\Models\WorkStoryPost;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class WorkStoryPostController extends Controller
{
    use SoftDeletes;

    // あらすじ感想投稿一覧の表示
    public function index($work_id, $work_story_id)
    {
        // 指定したidのあらすじの投稿のみを表示
        $work_story_posts = WorkStoryPost::where('sub_title_id', $work_story_id)->orderBy('id', 'DESC')->where(function ($query) {
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
        $work_story_post_first = WorkStoryPost::where('sub_title_id', $work_story_id)->first();
        return view('work_story_posts.index')->with(['work_story_posts' => $work_story_posts, 'work_story_post_first' => $work_story_post_first, 'work_id' => $work_id, 'work_story_id' => $work_story_id]);
    }

    // あらすじ感想投稿詳細の表示
    public function show(WorkStoryPost $workStoryPost, $work_id, $work_story_id, $work_story_post_id)
    {
        return view('work_story_posts.show')->with(['work_story_post' => $workStoryPost->getDetailPost($work_story_id, $work_story_post_id)]);
    }

    // 新規投稿作成画面を表示する
    public function create(WorkStoryPost $workStoryPost, $work_id, $work_story_id)
    {
        $work_story = WorkStory::where('id', $work_story_id)->first();
        return view('work_story_posts.create')->with(['work_story_post' => $workStoryPost->getRestrictedPost('sub_title_id', $work_story_id), 'work_story' => $work_story]);
    }

    // 新しく記述した内容を保存する
    public function store(WorkStoryPost $workStoryPost, WorkStoryPostRequest $request)
    {
        $input_post = $request['work_story_post'];
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
        $workStoryPost->fill($input_post)->save();
        return redirect()->route('work_story_posts.show', ['work_id' => $workStoryPost->work_id, 'work_story_id' => $workStoryPost->sub_title_id, 'work_story_post_id' => $workStoryPost->id]);
    }

    // 感想投稿編集画面を表示する
    public function edit(WorkStoryPost $workStoryPost, $work_id, $work_story_id, $work_story_post_id)
    {
        return view('work_story_posts.edit')->with(['work_story_post' => $workStoryPost->getDetailPost($work_story_id, $work_story_post_id)]);
    }

    // 感想投稿の編集を実行する
    public function update(WorkStoryPostRequest $request, WorkStoryPost $workStoryPost, $work_id, $work_story_id, $work_story_post_id)
    {
        $input_post = $request['work_story_post'];
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
                $image_path = Cloudinary::upload($image->getRealPath())->getSecurePath();
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
        $targetWorkStoryPost = $workStoryPost->getDetailPost($work_story_id, $work_story_post_id);
        $targetWorkStoryPost->fill($input_post)->save();
        return redirect()->route('work_story_posts.show', ['work_id' => $targetWorkStoryPost->work_id, 'work_story_id' => $targetWorkStoryPost->sub_title_id, 'work_story_post_id' => $targetWorkStoryPost->id]);
    }

    // 感想投稿を削除する
    public function delete(WorkStoryPost $workStoryPost, $work_id, $work_story_id, $work_story_post_id)
    {
        // 編集の対象となるデータを取得
        $targetWorkStoryPost = $workStoryPost->getDetailPost($work_story_id, $work_story_post_id);
        // 削除する投稿の画像も削除する処理
        for ($counter = 1; $counter < 5; $counter++) {
            $removed_image_path = $targetWorkStoryPost->{'image' . $counter};
            // DBのimageの中身がnullであれば処理をスキップする
            if (is_null($removed_image_path)) {
                break;
            }
            $public_id = $this->extractPublicIdFromUrl($removed_image_path);
            Cloudinary::destroy($public_id);
        }
        // データの削除
        $targetWorkStoryPost->delete();
        return redirect()->route('work_story_posts.index', ['work_id' => $targetWorkStoryPost->work_id, 'work_story_id' => $targetWorkStoryPost->sub_title_id]);
    }

    // 投稿にいいねを行う
    public function like($work_id, $work_story_id, $work_story_post_id)
    {
        // 投稿が見つからない場合の処理
        $work_story_post = WorkStoryPost::find($work_story_post_id);
        if (!$work_story_post) {
            return response()->json(['message' => 'Post not found'], 404);
        }
        // 現在ログインしているユーザーが既にいいねしていればtrueを返す
        $isLiked = $work_story_post->users()->where('user_id', Auth::id())->exists();
        if ($isLiked) {
            // 既にいいねしている場合
            $work_story_post->users()->detach(Auth::id());
            // いいねしたユーザー数の取得
            $count = count($work_story_post->users()->pluck('work_story_post_id')->toArray());
            return response()->json(['status' => 'unliked', 'like_user' => $count]);
        } else {
            // 初めてのいいねの場合
            $work_story_post->users()->attach(Auth::id());
            // いいねしたユーザー数の取得
            $count = count($work_story_post->users()->pluck('work_story_post_id')->toArray());
            return response()->json(['status' => 'liked', 'like_user' => $count]);
        }
        return back();
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
