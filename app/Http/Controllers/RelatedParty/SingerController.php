<?php

namespace App\Http\Controllers\RelatedParty;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Singer;

class SingerController extends Controller
{
    // 詳細な作詞者情報を表示する
    public function show($singer_id)
    {
        $singer = Singer::find($singer_id);
        return view('entities.related_party.singers.show')->with(['singer' => $singer]);
    }
}
