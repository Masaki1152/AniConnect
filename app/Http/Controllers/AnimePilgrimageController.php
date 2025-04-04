<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnimePilgrimage;
use App\Models\Prefecture;
use App\Models\AnimePilgrimagePostCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AnimePilgrimageController extends Controller
{
    // 聖地一覧画面の表示
    public function index(Request $request, AnimePilgrimage $animePilgrimage, Prefecture $prefecture, AnimePilgrimagePostCategory $category)
    {
        // 人気上位の聖地を取得
        $topPopularityPilgrimages = Cache::get('top_popular_pilgrimages');
        // キャッシュが見つからない場合
        if (!$topPopularityPilgrimages) {
            $sufficientPostsPilgrimages = $animePilgrimage->fetchSufficientPostNumPilgrimages();
            // updateTopPopularityItemsを実行して人気度の高い作品を再計算
            $animePilgrimage->updateTopPopularityItems($sufficientPostsPilgrimages, 'animePilgrimagePosts', 'top_popular_pilgrimages');
            // 再度キャッシュから人気度の高い作品を取得
            $topPopularityPilgrimages = Cache::get('top_popular_pilgrimages');
        }

        // クリックされたカテゴリーidを取得
        $categoryIds = $request->filled('checkedCategories')
            ? ($request->input('checkedCategories'))
            : [];
        // 県名モデルのカラム取得
        $prefectures = $prefecture->getLists();
        // 県名検索の値を取得
        $prefecture_search = request('prefecture_search');

        // 検索キーワードがあれば取得
        $search = $request->input('search', '');
        // キーワードに部分一致する聖地を取得
        $pilgrimages = $animePilgrimage->fetchAnimePilgrimages($search, $prefecture_search, $categoryIds);
        // 検索結果の件数を取得
        $totalResults = $pilgrimages->total();
        // 更新時間表示のために単体の聖地オブジェクトを取得
        $pilgrimage = AnimePilgrimage::find(1);

        // 各聖地の投稿数を追加　
        // 平均評価と異なりリアルタイム性が必要なため聖地一覧表示の度に取得
        $pilgrimages = $animePilgrimage->countPosts($pilgrimages, 'animePilgrimagePosts');

        // カテゴリー情報をまとめる
        foreach ($pilgrimages as $pilgrimage) {
            $pilgrimage->top_categories = collect([
                $pilgrimage->category_top_1,
                $pilgrimage->category_top_2,
                $pilgrimage->category_top_3,
            ])
                ->filter()
                ->map(function ($categoryId) {
                    $category = AnimePilgrimagePostCategory::find($categoryId);
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
            $category = AnimePilgrimagePostCategory::find($categoryId);
            array_push($selectedCategories, $category->name);
        }

        // 選択した検索の取得
        $selected_prefecture = null;
        if (!is_null($prefecture_search)) {
            $selected_prefecture = Prefecture::find($prefecture_search)->name;
        }

        return view('pilgrimages.index')->with([
            'pilgrimages' => $pilgrimages,
            'prefectures' => $prefectures,
            'prefecture_search' => $prefecture_search,
            'pilgrimage' => $pilgrimage,
            'categories' => $category->get(),
            'totalResults' => $totalResults,
            'search' => $search,
            'selected_prefecture' => $selected_prefecture,
            'selectedCategories' => $selectedCategories,
            'topPopularityPilgrimages' => $topPopularityPilgrimages
        ]);
    }

    // 詳細な聖地情報を表示する
    public function show($pilgrimage_id)
    {
        $pilgrimage = AnimePilgrimage::find($pilgrimage_id);

        $categories = [];
        // カテゴリーの情報を取得する
        foreach ([$pilgrimage->category_top_1, $pilgrimage->category_top_2, $pilgrimage->category_top_3] as $categoryId) {
            $category = AnimePilgrimagePostCategory::find($categoryId);
            if (!empty($category)) {
                array_push($categories, $category->name);
            }
        }

        return view('pilgrimages.show')->with(['pilgrimage' => $pilgrimage, 'categories' => $categories]);
    }

    // 聖地に「気になる」登録をする
    public function interested($pilgrimage_id)
    {
        // 聖地が見つからない場合の処理
        $pilgrimage = AnimePilgrimage::find($pilgrimage_id);
        if (!$pilgrimage) {
            $message = __('messages.pilgrimage_not_found');
            return response()->json(['message' => $message], 404);
        }
        // 現在ログインしているユーザーが既に「気になる」登録していればtrueを返す
        $isInterested = $pilgrimage->users()->where('user_id', Auth::id())->exists();
        if ($isInterested) {
            // 既に「気になる」登録している場合
            $pilgrimage->users()->detach(Auth::id());
            $status = 'unInterested';
            $message = __('messages.unmarked_as_interested');
        } else {
            // 初めての「気になる」登録の場合
            $pilgrimage->users()->attach(Auth::id());
            $status = 'interested';
            $message = __('messages.marked_as_interested');
        }
        // 「気になる」登録したユーザー数の取得
        $count = count($pilgrimage->users()->pluck('anime_pilgrimage_id')->toArray());

        return response()->json(['status' => $status, 'interested_user' => $count, 'message' => $message]);
    }
}
