<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkStoryPostRequest;
use App\Models\WorkStoryPost;
use Illuminate\Support\Facades\Auth;

class WorkStoryPostController extends Controller
{
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
        return view('work_story_posts.index')->with(['work_story_posts' => $work_story_posts, 'work_story_post_first' => $work_story_post_first]);
    }

    // あらすじ感想投稿詳細の表示
    public function show(WorkStoryPost $workStoryPost, $work_id, $work_story_id, $work_story_post_id)
    {
        return view('work_story_posts.show')->with(['work_story_post' => $workStoryPost->getDetailPost($work_story_id, $work_story_post_id)]);
    }

    // 新規投稿作成画面を表示する
    public function create(WorkStoryPost $workStoryPost, $work_id, $work_story_id)
    {
        return view('work_story_posts.create')->with(['work_story_post' => $workStoryPost->getRestrictedPost('sub_title_id', $work_story_id)]);
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
}
