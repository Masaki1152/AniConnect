<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Composer;

class ComposerController extends Controller
{
    // 詳細な作曲者情報を表示する
    public function show($composer_id)
    {
        $composer = Composer::find($composer_id);
        return view('composers.show')->with(['composer' => $composer]);
    }
}
