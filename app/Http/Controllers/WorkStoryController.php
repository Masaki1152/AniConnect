<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkStory;

class WorkStoryController extends Controller
{
    // あらすじ一覧画面の表示
    public function index(Request $request, WorkStory $workStory, $work_id)
    {
        // 検索キーワードがあれば取得
        $search = $request->input('search', '');
        // キーワードに部分一致するあらすじを取得
        $work_stories = $workStory->fetchWorkStories($search, $work_id);
        // あらすじのオブジェクトを1つ取得
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
