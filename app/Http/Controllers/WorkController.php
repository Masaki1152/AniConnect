<?php

namespace App\Http\Controllers;

use App\Models\Work;
use Illuminate\Http\Request;

class WorkController extends Controller
{
    // 作品一覧画面の表示
    public function index(Work $work)
    {
        return view('works.index')->with(['works' => $work->get()]);
    }
}
