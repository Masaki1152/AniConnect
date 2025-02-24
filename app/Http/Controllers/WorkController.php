<?php

namespace App\Http\Controllers;

use App\Models\Work;
use App\Models\WorkReviewCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    // 作品に「気になる」登録をする
    public function interested($work_id)
    {
        // 投稿が見つからない場合の処理
        $work = Work::find($work_id);
        if (!$work) {
            return response()->json(['message' => '作品がありません'], 404);
        }
        // 現在ログインしているユーザーが既に「気になる」登録していればtrueを返す
        $isInterested = $work->users()->where('user_id', Auth::id())->exists();
        if ($isInterested) {
            // 既に「気になる」登録している場合
            $work->users()->detach(Auth::id());
            $status = 'unInterested';
            $message = '「気になる」登録を解除しました';
        } else {
            // 初めての「気になる」登録の場合
            $work->users()->attach(Auth::id());
            $status = 'interested';
            $message = '「気になる」登録しました';
        }
        // 「気になる」登録したユーザー数の取得
        $count = count($work->users()->pluck('work_id')->toArray());

        return response()->json(['status' => $status, 'interested_user' => $count, 'message' => $message]);
    }
}
