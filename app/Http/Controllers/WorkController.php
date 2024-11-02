<?php

namespace App\Http\Controllers;

use App\Models\Work;
use Illuminate\Http\Request;

class WorkController extends Controller
{
    // 作品一覧画面の表示
    public function index()
    {
        $works = Work::orderBy('id', 'ASC')->where(function($query) {
            // キーワード検索がなされた場合
            if($search = request('search')) {
                $query->where('name', 'LIKE', "%{$search}%");
            }
        })->paginate(5);
        return view('works.index')->with(['works' => $works]);
    }

    // 詳細な作品情報を表示する
    public function show(Work $work)
    {
        return view('works.show')->with(['work' => $work]);
    }
}
