<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkStory;

class WorkStoryController extends Controller
{
    // あらすじ一覧画面の表示
    public function index($work_id)
    {
        $work_stories = WorkStory::where('work_id', '=', $work_id)->orderBy('id', 'ASC')->where(function($query) {
            
            // キーワード検索がなされた場合
            if ($search = request('search')) {
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
        // あらすじのモデルを1つ取得
        $work_story_model = WorkStory::where('work_id', '=', $work_id)->first();
        return view('work_stories.index')->with(['work_stories' => $work_stories, 'work_story_model' => $work_story_model]);
    }

    // 詳細なあらすじ情報を表示する
    public function show($work_id, $work_story_id)
    {
        $work_story = WorkStory::find($work_story_id);
        return view('work_stories.show')->with(['work_story' => $work_story]);
    }
}
