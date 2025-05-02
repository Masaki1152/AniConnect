<?php

namespace App\Http\Controllers\WorkStory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WorkStory;
use App\Models\WorkStoryPostCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class WorkStoryController extends Controller
{
    // あらすじ一覧画面の表示
    public function index(Request $request, WorkStory $workStory, WorkStoryPostCategory $category, $work_id)
    {
        // 人気上位のあらすじを取得(毎度作品ごとに異なるためforgetを行う)
        Cache::forget('top_popular_work_stories');
        $sufficientReviewsWorkStories = $workStory->fetchSufficientReviewNumWorkStories($work_id);
        // updateTopPopularityItemsを実行して人気度の高い作品を再計算
        $workStory->updateTopPopularityItems($sufficientReviewsWorkStories, 'workStoryPosts', 'top_popular_work_stories');
        // キャッシュから人気度の高い作品を取得
        $topPopularityWorkStories = Cache::get('top_popular_work_stories');

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

        // 各あらすじの感想投稿数を追加　
        // 平均評価と異なりリアルタイム性が必要なためあらすじ一覧表示の度に取得
        $work_stories = $workStory->countPosts($work_stories, 'workStoryPosts');

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
                        'name' => $category->name ?? __('messages.unknown_category'),
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

        return view('entities.work_stories.index')->with([
            'work_stories' => $work_stories,
            'work_story_model' => $work_story_model,
            'categories' => $category->get(),
            'totalResults' => $totalResults,
            'search' => $search,
            'selectedCategories' => $selectedCategories,
            'topPopularityWorkStories' => $topPopularityWorkStories
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
        return view('entities.work_stories.show')->with(['work_story' => $work_story, 'categories' => $categories]);
    }

    // 作品に「気になる」登録をする
    public function interested($work_id, $work_story_id)
    {
        // 投稿が見つからない場合の処理
        $workStory = WorkStory::find($work_story_id);
        if (!$workStory) {
            $message = __('messages.work_story_not_found');
            return response()->json(['message' => $message], 404);
        }
        // 現在ログインしているユーザーが既に「気になる」登録していればtrueを返す
        $isInterested = $workStory->users()->where('user_id', Auth::id())->exists();
        if ($isInterested) {
            // 既に「気になる」登録している場合
            $workStory->users()->detach(Auth::id());
            $status = 'unInterested';
            $message = __('messages.unmarked_as_interested');
        } else {
            // 初めての「気になる」登録の場合
            $workStory->users()->attach(Auth::id());
            $status = 'interested';
            $message = __('messages.marked_as_interested');
        }
        // 「気になる」登録したユーザー数の取得
        $count = count($workStory->users()->pluck('work_story_id')->toArray());

        return response()->json(['status' => $status, 'interested_user' => $count, 'message' => $message]);
    }
}
