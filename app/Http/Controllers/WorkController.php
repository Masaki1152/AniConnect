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
        return view('works.index')->with(['works' => $works]);
    }

    // 詳細な作品情報を表示する
    public function show(Work $work)
    {
        return view('works.show')->with(['work' => $work]);
    }
}
