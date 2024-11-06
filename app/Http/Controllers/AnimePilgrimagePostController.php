<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnimePilgrimagePost;

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
}
