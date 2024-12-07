<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkStory extends Model
{
    use HasFactory;

    // 参照させたいwork_storiesを指定
    protected $table = 'work_stories';

    // あらすじの検索処理
    public function fetchWorkStories($search, $work_id)
    {
        $work_stories = WorkStory::where('work_id', '=', $work_id)->orderBy('id', 'ASC')->where(function ($query) use ($search) {

            // キーワード検索がなされた場合
            if ($search) {
                // 検索語のスペースを半角に統一
                $search_split = mb_convert_kana($search, 's');
                // 半角スペースで単語ごとに分割して配列にする
                $search_array = preg_split('/[\s]+/', $search_split);
                foreach ($search_array as $search_word) {
                    $query->where(function ($query) use ($search_word) {
                        $query->where('episode', 'LIKE', "%{$search_word}%")
                            ->orWhere('sub_title', 'LIKE', "%{$search_word}%")
                            ->orWhere('body', 'LIKE', "%{$search_word}%");
                    });
                }
            }
        })->paginate(20);
        return $work_stories;
    }

    // Workに対するリレーション 1対多の関係
    public function work()
    {
        return $this->belongsTo(Work::class, 'work_id', 'id');
    }

    // WorkStoryPostに対するリレーション 1対1の関係
    public function workStoryPost()
    {
        return $this->hasOne(WorkStoryPost::class, 'id', 'sub_title_id');
    }
}
