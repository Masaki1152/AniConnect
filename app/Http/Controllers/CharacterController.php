<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Character;
use App\Models\CharacterPostCategory;

class CharacterController extends Controller
{
    // 登場人物一覧画面の表示
    public function index(Request $request, Character $character, CharacterPostCategory $category)
    {
        // クリックされたカテゴリーidを取得
        $categoryIds = $request->filled('checkedCategories')
            ? ($request->input('checkedCategories'))
            : [];
        // 検索キーワードがあれば取得
        $search = $request->input('search', '');
        // キーワードに部分一致する登場人物を取得
        $characters = $character->fetchCharacters($search, $categoryIds);
        // 更新時間表示のために単体の登場人物オブジェクトを取得
        $character = Character::find(1);

        return view('characters.index')->with(['characters' => $characters, 'character' => $character, 'categories' => $category->get()]);
    }

    // 詳細な登場人物情報を表示する
    public function show($character_id)
    {
        $character = Character::find($character_id);
        return view('characters.show')->with(['character' => $character]);
    }
}
