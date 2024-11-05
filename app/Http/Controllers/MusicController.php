<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Music;

class MusicController extends Controller
{
    // 音楽画面の表示
    public function index()
    {
        $music = Music::orderBy('id', 'ASC')->where(function($query) {
            // キーワード検索がなされた場合
            if ($search = request('search')) {
                // 検索語のスペースを半角に統一
                $search_split = mb_convert_kana($search, 's');
                // 半角スペースで単語ごとに分割して配列にする
                $search_array = preg_split('/[\s]+/', $search_split);
                foreach ($search_array as $search_word) {
                    $query->where(function ($query) use ($search_word) {
                        $query->where('name', 'LIKE', "%{$search_word}%");
                    });
                }
            }
        })->paginate(5);
        return view('music.index')->with(['music' => $music]);
    }

    // 詳細な音楽情報を表示する
    public function show($music_id)
    {
        $music = Music::find($music_id);
        return view('music.show')->with(['music' => $music]);
    }
}
