<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MusicPost;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class MusicPostController extends Controller
{
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
}
