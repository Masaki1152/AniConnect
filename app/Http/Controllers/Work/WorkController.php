<?php

namespace App\Http\Controllers\Work;

use App\Http\Controllers\Controller;
use App\Models\Work;
use App\Models\WorkPostCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class WorkController extends Controller
{
    // 作品一覧画面の表示
    public function index(Request $request, Work $work, WorkPostCategory $category)
    {
        // 人気上位の作品を取得
        $topPopularityWorks = Cache::get('top_popular_works');
        // キャッシュが見つからない場合
        if (!$topPopularityWorks) {
            $sufficientPostsWorks = $work->fetchSufficientPostNumWorks();
            // updateTopPopularityWorksを実行して人気度の高い作品を再計算
            $work->updateTopPopularityItems($sufficientPostsWorks, 'workPosts', 'top_popular_works');
            // 再度キャッシュから人気度の高い作品を取得
            $topPopularityWorks = Cache::get('top_popular_works');
        }

        // クリックされたカテゴリーidを取得
        $categoryIds = $request->filled('checkedCategories')
            ? ($request->input('checkedCategories'))
            : [];
        // 検索キーワードがあれば取得
        $search = $request->input('search', '');
        // キーワードに部分一致する作品を取得
        $works = $work->fetchWorks($search, $categoryIds);
        // 検索結果の件数を取得
        $totalResults = $works->total();
        // 更新時間表示のために単体の作品オブジェクトを取得
        $work = Work::find(1);

        // 各作品の投稿数を追加　
        // 平均評価と異なりリアルタイム性が必要なため作品一覧表示の度に取得
        $works = $work->countPosts($works, 'workPosts');

        // カテゴリー情報をまとめる
        foreach ($works as $work) {
            $work->top_categories = collect([
                $work->category_top_1,
                $work->category_top_2,
                $work->category_top_3,
            ])
                ->filter()
                ->map(function ($categoryId) {
                    $category = WorkPostCategory::find($categoryId);
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
            $category = WorkPostCategory::find($categoryId);
            array_push($selectedCategories, $category->name);
        }

        return view('entities.works.index')->with([
            'works' => $works,
            'work' => $work,
            'categories' => $category->get(),
            'totalResults' => $totalResults,
            'search' => $search,
            'selectedCategories' => $selectedCategories,
            'topPopularityWorks' => $topPopularityWorks
        ]);
    }

    // 詳細な作品情報を表示する
    public function show(Work $work)
    {
        $categories = [];
        // カテゴリーの情報を取得する
        foreach ([$work->category_top_1, $work->category_top_2, $work->category_top_3] as $categoryId) {
            $category = WorkPostCategory::find($categoryId);
            if (!empty($category)) {
                array_push($categories, $category->name);
            }
        }
        return view('entities.works.show')->with(['work' => $work, 'categories' => $categories]);
    }

    // 作品に「気になる」登録をする
    public function interested($work_id)
    {
        // 投稿が見つからない場合の処理
        $work = Work::find($work_id);
        if (!$work) {
            $message = __('messages.work_not_found');
            return response()->json(['message' => $message], 404);
        }
        // 現在ログインしているユーザーが既に「気になる」登録していればtrueを返す
        $isInterested = $work->users()->where('user_id', Auth::id())->exists();
        if ($isInterested) {
            // 既に「気になる」登録している場合
            $work->users()->detach(Auth::id());
            $status = 'unInterested';
            $message = __('messages.unmarked_as_interested');
        } else {
            // 初めての「気になる」登録の場合
            $work->users()->attach(Auth::id());
            $status = 'interested';
            $message = __('messages.marked_as_interested');
        }
        // 「気になる」登録したユーザー数の取得
        $count = count($work->users()->pluck('work_id')->toArray());

        return response()->json(['status' => $status, 'interested_user' => $count, 'message' => $message]);
    }
}
