<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Music;
use App\Models\MusicPostCategory;

class MusicController extends Controller
{
    // 音楽画面の表示
    public function index(Request $request, Music $music, MusicPostCategory $category)
    {
        // クリックされたカテゴリーidを取得
        $categoryIds = $request->filled('checkedCategories')
            ? ($request->input('checkedCategories'))
            : [];
        // 検索キーワードがあれば取得
        $search = $request->input('search', '');
        // キーワードに部分一致する音楽を取得
        $music = $music->fetchMusic($search, $categoryIds);
        // 検索結果の件数を取得
        $totalResults = $music->total();
        // 更新時間表示のために単体の音楽オブジェクトを取得
        $music_object = Music::find(1);

        // カテゴリー情報をまとめる
        foreach ($music as $one_of_music) {
            $one_of_music->top_categories = collect([
                $one_of_music->category_top_1,
                $one_of_music->category_top_2,
                $one_of_music->category_top_3,
            ])
                ->filter()
                ->map(function ($categoryId) {
                    $category = MusicPostCategory::find($categoryId);
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
            $category = MusicPostCategory::find($categoryId);
            array_push($selectedCategories, $category->name);
        }

        return view('music.index')->with([
            'music' => $music,
            'music_object' => $music_object,
            'categories' => $category->get(),
            'totalResults' => $totalResults,
            'search' => $search,
            'selectedCategories' => $selectedCategories
        ]);
    }

    // 詳細な音楽情報を表示する
    public function show($music_id)
    {
        $music = Music::find($music_id);

        $categories = [];
        // カテゴリーの情報を取得する
        foreach ([$music->category_top_1, $music->category_top_2, $music->category_top_3] as $categoryId) {
            $category = MusicPostCategory::find($categoryId);
            if (!empty($category)) {
                array_push($categories, $category->name);
            }
        }

        return view('music.show')->with(['music' => $music, 'categories' => $categories]);
    }
}
