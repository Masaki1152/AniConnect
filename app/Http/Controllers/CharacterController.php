<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Character;

class CharacterController extends Controller
{
    // 作品一覧画面の表示
    public function index()
    {
        $characters = Character::orderBy('id', 'ASC')->where(function($query) {
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
        return view('characters.index')->with(['characters' => $characters]);
    }

    // 詳細な作品情報を表示する
    public function show(Work $work)
    {
        //$creator = Creator::find($creator_id);
        return view('works.show')->with(['work' => $work]);
    }
}
