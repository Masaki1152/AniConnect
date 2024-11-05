<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Singer;

class SingerController extends Controller
{
    // 詳細な作詞者情報を表示する
    public function show($singer_id)
    {
        $singer = Singer::find($singer_id);
        return view('singers.show')->with(['singer' => $singer]);
    }
}
