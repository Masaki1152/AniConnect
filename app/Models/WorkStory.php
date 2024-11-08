<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkStory extends Model
{
    use HasFactory;

    // 参照させたいwork_storiesを指定
    protected $table = 'work_stories';

    // Workに対するリレーション 1対多の関係
    public function work()
    {
        return $this->belongsTo(Work::class, 'work_id', 'id');
    }
}
