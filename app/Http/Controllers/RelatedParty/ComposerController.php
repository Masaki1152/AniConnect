<?php

namespace App\Http\Controllers\RelatedParty;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Composer;

class ComposerController extends Controller
{
    // 詳細な作曲者情報を表示する
    public function show($composer_id)
    {
        $composer = Composer::find($composer_id);
        return view('related_party.composers.show')->with(['composer' => $composer]);
    }
}
