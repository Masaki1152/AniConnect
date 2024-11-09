<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VoiceArtist;

class VoiceArtistController extends Controller
{
    // 詳細な声優情報を表示する
    public function show($voice_artist_id)
    {
        $voice_artist = VoiceArtist::find($voice_artist_id);
        return view('voice_artists.show')->with(['voice_artist' => $voice_artist]);
    }
}
