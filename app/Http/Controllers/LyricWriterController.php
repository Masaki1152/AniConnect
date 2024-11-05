<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LyricWriter;

class LyricWriterController extends Controller
{
    // 詳細な作詞者情報を表示する
    public function show($lyric_writer_id)
    {
        $lyric_writer = LyricWriter::find($lyric_writer_id);
        return view('lyric_writers.show')->with(['lyric_writer' => $lyric_writer]);
    }
}
