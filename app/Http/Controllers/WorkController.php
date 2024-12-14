<?php

namespace App\Http\Controllers;

use App\Models\Work;
use App\Models\WorkReviewCategory;
use Illuminate\Http\Request;

class WorkController extends Controller
{
    // 作品一覧画面の表示
    public function index(Request $request, Work $work, WorkReviewCategory $category)
    {
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

        // カテゴリー情報をまとめる
        foreach ($works as $work) {
            $work->top_categories = collect([
                $work->category_top_1,
                $work->category_top_2,
                $work->category_top_3,
            ])
                ->filter()
                ->map(function ($categoryId) {
                    $category = WorkReviewCategory::find($categoryId);
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
            $category = WorkReviewCategory::find($categoryId);
            array_push($selectedCategories, $category->name);
        }

        return view('works.index')->with([
            'works' => $works,
            'work' => $work,
            'categories' => $category->get(),
            'totalResults' => $totalResults,
            'search' => $search,
            'selectedCategories' => $selectedCategories
        ]);
    }

    // 詳細な作品情報を表示する
    public function show(Work $work)
    {
        $categories = [];
        // カテゴリーの情報を取得する
        foreach ([$work->category_top_1, $work->category_top_2, $work->category_top_3] as $categoryId) {
            $category = WorkReviewCategory::find($categoryId);
            if (!empty($category)) {
                array_push($categories, $category->name);
            }
        }
        return view('works.show')->with(['work' => $work, 'categories' => $categories]);
    }
}
