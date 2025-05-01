<?php

namespace App\Http\Controllers\RelatedParty;

use App\Http\Controllers\Controller;
use App\Models\Creator;
use Illuminate\Http\Request;

class CreatorController extends Controller
{
    // 詳細な作品情報を表示する
    public function show($creator_id)
    {
        $creator = Creator::find($creator_id);
        return view('related_party.creators.show')->with(['creator' => $creator]);
    }
}
