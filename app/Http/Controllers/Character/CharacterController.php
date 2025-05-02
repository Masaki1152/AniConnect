<?php

namespace App\Http\Controllers\Character;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Character;
use App\Models\CharacterPostCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CharacterController extends Controller
{
    // 登場人物一覧画面の表示
    public function index(Request $request, Character $character, CharacterPostCategory $category)
    {
        // 人気上位の登場人物を取得
        $topPopularityCharacters = Cache::get('top_popular_characters');
        // キャッシュが見つからない場合
        if (!$topPopularityCharacters) {
            $sufficientPostsCharacters = $character->fetchSufficientPostNumCharacters();
            // updateTopPopularityItemsを実行して人気度の高い登場人物を再計算
            $character->updateTopPopularityItems($sufficientPostsCharacters, 'CharacterPosts', 'top_popular_characters');
            // 再度キャッシュから人気度の高い登場人物を取得
            $topPopularityCharacters = Cache::get('top_popular_characters');
        }

        // クリックされたカテゴリーidを取得
        $categoryIds = $request->filled('checkedCategories')
            ? ($request->input('checkedCategories'))
            : [];
        // 検索キーワードがあれば取得
        $search = $request->input('search', '');
        // キーワードに部分一致する登場人物を取得
        $characters = $character->fetchCharacters($search, $categoryIds);
        // 検索結果の件数を取得
        $totalResults = $characters->total();
        // 更新時間表示のために単体の登場人物オブジェクトを取得
        $character = Character::find(1);

        // 各登場人物の投稿数を追加　
        // 平均評価と異なりリアルタイム性が必要なため登場人物一覧表示の度に取得
        $characters = $character->countPosts($characters, 'characterPosts');

        // カテゴリー情報をまとめる
        foreach ($characters as $character) {
            $character->top_categories = collect([
                $character->category_top_1,
                $character->category_top_2,
                $character->category_top_3,
            ])
                ->filter()
                ->map(function ($categoryId) {
                    $category = CharacterPostCategory::find($categoryId);
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
            $category = CharacterPostCategory::find($categoryId);
            array_push($selectedCategories, $category->name);
        }

        return view('entities.characters.index')->with([
            'characters' => $characters,
            'character' => $character,
            'categories' => $category->get(),
            'totalResults' => $totalResults,
            'search' => $search,
            'selectedCategories' => $selectedCategories,
            'topPopularityCharacters' => $topPopularityCharacters
        ]);
    }

    // 詳細な登場人物情報を表示する
    public function show($character_id)
    {
        $character = Character::find($character_id);

        $categories = [];
        // カテゴリーの情報を取得する
        foreach ([$character->category_top_1, $character->category_top_2, $character->category_top_3] as $categoryId) {
            $category = CharacterPostCategory::find($categoryId);
            if (!empty($category)) {
                array_push($categories, $category->name);
            }
        }
        return view('entities.characters.show')->with(['character' => $character, 'categories' => $categories]);
    }

    // 登場人物に「気になる」登録をする
    public function interested($character_id)
    {
        // 登場人物が見つからない場合の処理
        $character = Character::find($character_id);
        if (!$character) {
            $message = __('messages.character_not_found');
            return response()->json(['message' => $message], 404);
        }
        // 現在ログインしているユーザーが既に「気になる」登録していればtrueを返す
        $isInterested = $character->users()->where('user_id', Auth::id())->exists();
        if ($isInterested) {
            // 既に「気になる」登録している場合
            $character->users()->detach(Auth::id());
            $status = 'unInterested';
            $message = __('messages.unmarked_as_interested');
        } else {
            // 初めての「気になる」登録の場合
            $character->users()->attach(Auth::id());
            $status = 'interested';
            $message = __('messages.marked_as_interested');
        }
        // 「気になる」登録したユーザー数の取得
        $count = count($character->users()->pluck('character_id')->toArray());

        return response()->json(['status' => $status, 'interested_user' => $count, 'message' => $message]);
    }
}
