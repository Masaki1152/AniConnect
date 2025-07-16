<?php

namespace App\Http\Controllers\RelatedParty;

use App\Http\Controllers\Controller;
use App\Models\Creator;
use Illuminate\Http\Request;

class CreatorController extends Controller
{
    // 制作会社を検索する
    public function search(Request $request)
    {
        $query = $request->input('q');
        $creators = Creator::where('name', 'like', '%' . $query . '%')->get(['id', 'name']);

        return response()->json($creators);
    }

    // 詳細な作品情報を表示する
    public function show($creator_id)
    {
        $creator = Creator::find($creator_id);
        return view('entities.related_party.creators.show')->with(['creator' => $creator]);
    }
}
