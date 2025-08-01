<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Work;
use App\Models\Character;
use App\Models\Notification;
use Illuminate\Support\Facades\Cache;


class MainController extends Controller
{
    // メイン画面の表示
    public function index(Work $work, Character $character, Notification $notification)
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

        // 最新のお知らせ4件の取得
        $notifications = $notification->getRecentNotifications();
        return view('main.index', compact('notifications', 'topPopularityWorks', 'topPopularityCharacters'));
    }
}
