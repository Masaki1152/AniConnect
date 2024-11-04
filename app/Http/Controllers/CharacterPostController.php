<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CharacterPost;
use App\Models\CharacterPostCategory;

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
}
