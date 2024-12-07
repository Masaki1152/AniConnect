<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Music;

class MusicController extends Controller
{
    // 音楽画面の表示
    public function index(Request $request, Music $music)
    {
        // 検索キーワードがあれば取得
        $search = $request->input('search', '');
        // キーワードに部分一致する音楽を取得
        $music = $music->fetchMusic($search);
        return view('music.index')->with(['music' => $music]);
    }

    // 詳細な音楽情報を表示する
    public function show($music_id)
    {
        $music = Music::find($music_id);
        return view('music.show')->with(['music' => $music]);
    }
}
