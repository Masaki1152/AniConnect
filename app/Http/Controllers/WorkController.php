<?php

namespace App\Http\Controllers;

use App\Models\Work;
use Illuminate\Http\Request;

class WorkController extends Controller
{
    // 作品一覧画面の表示
    public function index(Request $request, Work $work)
    {
        // 検索キーワードがあれば取得
        $search = $request->input('search', '');
        // キーワードに部分一致する作品を取得
        $works = $work->fetchWorks($search);
        // 更新時間表示のために単体の作品オブジェクトを取得
        $work = Work::find(1);

        return view('works.index')->with(['works' => $works, 'work' => $work]);
    }

    // 詳細な作品情報を表示する
    public function show(Work $work)
    {
        return view('works.show')->with(['work' => $work]);
    }
}
