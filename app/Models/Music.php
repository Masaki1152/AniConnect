<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Music extends Model
{
    use HasFactory;

    // 参照させたいmusicを指定
    protected $table = 'music';

    // 音楽の検索処理
    public function fetchMusic($search)
    {
        $music = Music::orderBy('id', 'ASC')->where(function ($query) use ($search) {
            // キーワード検索がなされた場合
            if ($search) {
                // 検索語のスペースを半角に統一
                $search_split = mb_convert_kana($search, 's');
                // 半角スペースで単語ごとに分割して配列にする
                $search_array = preg_split('/[\s]+/', $search_split);
                foreach ($search_array as $search_word) {
                    $query->where(function ($query) use ($search_word) {
                        $query->where('name', 'LIKE', "%{$search_word}%");
                    });
                }
            }
        })->paginate(5);
        return $music;
    }

    // Workに対するリレーション 1対多の関係
    public function work()
    {
        return $this->belongsTo(Work::class, 'work_id', 'id');
    }

    // Composerに対するリレーション 1対多の関係
    public function composer()
    {
        return $this->belongsTo(Composer::class, 'composer_id', 'id');
    }

    // LyricWriterに対するリレーション 1対多の関係
    public function lyricWriter()
    {
        return $this->belongsTo(LyricWriter::class, 'lyric_writer_id', 'id');
    }

    // Singerに対するリレーション 1対多の関係
    public function singer()
    {
        return $this->belongsTo(Singer::class, 'singer_id', 'id');
    }

    // MusicPostに対するリレーション 1対1の関係
    public function musicPost()
    {
        return $this->hasOne(MusicPost::class, 'id', 'music_id');
    }
}
