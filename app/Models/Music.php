<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Music extends Model
{
    use HasFactory;

    // 参照させたいmusicを指定
    protected $table = 'music';

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
}
