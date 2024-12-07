<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Character;

class CharacterController extends Controller
{
    // 作品一覧画面の表示
    public function index(Request $request, Character $character)
    {
        // 検索キーワードがあれば取得
        $search = $request->input('search', '');
        // キーワードに部分一致する音楽を取得
        $characters = $character->fetchCharacters($search);
        return view('characters.index')->with(['characters' => $characters]);
    }

    // 詳細な作品情報を表示する
    public function show($character_id)
    {
        $character = Character::find($character_id);
        return view('characters.show')->with(['character' => $character]);
    }
}
