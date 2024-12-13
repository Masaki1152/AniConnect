<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Music;
use App\Models\MusicPostCategory;

class MusicController extends Controller
{
    // 音楽画面の表示
    public function index(Request $request, Music $music, MusicPostCategory $category)
    {
        // クリックされたカテゴリーidを取得
        $categoryIds = $request->filled('checkedCategories')
            ? ($request->input('checkedCategories'))
            : [];
        // 検索キーワードがあれば取得
        $search = $request->input('search', '');
        // キーワードに部分一致する音楽を取得
        $music = $music->fetchMusic($search, $categoryIds);
        // 更新時間表示のために単体の音楽オブジェクトを取得
        $music_object = Music::find(1);
        return view('music.index')->with(['music' => $music, 'music_object' => $music_object, 'categories' => $category->get()]);
    }

    // 詳細な音楽情報を表示する
    public function show($music_id)
    {
        $music = Music::find($music_id);
        return view('music.show')->with(['music' => $music]);
    }
}
