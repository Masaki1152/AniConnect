<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnimePilgrimage;

class AnimePilgrimageController extends Controller
{
    // 聖地一覧画面の表示
    public function index()
    {
        $pilgrimages = AnimePilgrimage::orderBy('id', 'ASC')->where(function ($query) {
            // キーワード検索がなされた場合
            if ($search = request('search')) {
                // 検索語のスペースを半角に統一
                $search_split = mb_convert_kana($search, 's');
                // 半角スペースで単語ごとに分割して配列にする
                $search_array = preg_split('/[\s]+/', $search_split);
                foreach ($search_array as $search_word) {
                    $query->where(function ($query) use ($search_word) {
                        $query->where('name', 'LIKE', "%{$search_word}%")
                            ->orWhere('place', 'LIKE', "%{$search_word}%");
                    });
                }
            }
        })->paginate(5);
        return view('pilgrimages.index')->with(['pilgrimages' => $pilgrimages]);
    }

    // 詳細な聖地情報を表示する
    public function show($pilgrimage_id)
    {
        $pilgrimage = AnimePilgrimage::find($pilgrimage_id);
        return view('pilgrimages.show')->with(['pilgrimage' => $pilgrimage]);
    }
}
