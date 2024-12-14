<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkStory;
use App\Models\WorkStoryPostCategory;

class WorkStoryController extends Controller
{
    // あらすじ一覧画面の表示
    public function index(Request $request, WorkStory $workStory, WorkStoryPostCategory $category, $work_id)
    {
        // クリックされたカテゴリーidを取得
        $categoryIds = $request->filled('checkedCategories')
            ? ($request->input('checkedCategories'))
            : [];
        // 検索キーワードがあれば取得
        $search = $request->input('search', '');
        // キーワードに部分一致するあらすじを取得
        $work_stories = $workStory->fetchWorkStories($search, $work_id, $categoryIds);
        // 検索結果の件数を取得
        $totalResults = $work_stories->total();
        // あらすじのオブジェクトを1つ取得
        $work_story_model = WorkStory::where('work_id', '=', $work_id)->first();
        // カテゴリー情報をまとめる
        foreach ($work_stories as $work_story) {
            $work_story->top_categories = collect([
                $work_story->category_top_1,
                $work_story->category_top_2,
                $work_story->category_top_3,
            ])
                ->filter()
                ->map(function ($categoryId) {
                    $category = WorkStoryPostCategory::find($categoryId);
                    return [
                        'name' => $category->name ?? '不明なカテゴリー',
                        'color' => getCategoryColor($category->name ?? ''),
                    ];
                });
        }

        // カテゴリー検索で選択されたカテゴリーをまとめる
        $selectedCategories = [];
        // カテゴリーの情報を取得する
        foreach ($categoryIds as $categoryId) {
            $category = WorkStoryPostCategory::find($categoryId);
            array_push($selectedCategories, $category->name);
        }

        return view('work_stories.index')->with([
            'work_stories' => $work_stories,
            'work_story_model' => $work_story_model,
            'categories' => $category->get(),
            'totalResults' => $totalResults,
            'search' => $search,
            'selectedCategories' => $selectedCategories
        ]);
    }

    // 詳細なあらすじ情報を表示する
    public function show($work_id, $work_story_id)
    {
        $work_story = WorkStory::find($work_story_id);
        $categories = [];
        // カテゴリーの情報を取得する
        foreach ([$work_story->category_top_1, $work_story->category_top_2, $work_story->category_top_3] as $categoryId) {
            $category = WorkStoryPostCategory::find($categoryId);
            if (!empty($category)) {
                array_push($categories, $category->name);
            }
        }
        return view('work_stories.show')->with(['work_story' => $work_story, 'categories' => $categories]);
    }
}
