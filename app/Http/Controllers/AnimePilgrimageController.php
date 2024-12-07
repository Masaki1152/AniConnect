<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnimePilgrimage;
use App\Models\Prefecture;

class AnimePilgrimageController extends Controller
{
    // 聖地一覧画面の表示
    public function index(Request $request, AnimePilgrimage $animePilgrimage, Prefecture $prefecture)
    {
        // 県名モデルのカラム取得
        $prefectures = $prefecture->getLists();
        // 県名検索の値を取得
        $prefecture_search = request('prefecture_search');

        // 検索キーワードがあれば取得
        $search = $request->input('search', '');
        // キーワードに部分一致する聖地を取得
        $pilgrimages = $animePilgrimage->fetchAnimePilgrimages($search, $prefecture_search);
        return view('pilgrimages.index')->with(['pilgrimages' => $pilgrimages, 'prefectures' => $prefectures, 'prefecture_search' => $prefecture_search]);
    }

    // 詳細な聖地情報を表示する
    public function show($pilgrimage_id)
    {
        $pilgrimage = AnimePilgrimage::find($pilgrimage_id);
        return view('pilgrimages.show')->with(['pilgrimage' => $pilgrimage]);
    }
}
