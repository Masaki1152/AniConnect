<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Work;
use App\Models\WorkStory;
use App\Models\Character;
use App\Models\Music;
use App\Models\AnimePilgrimage;
use App\Models\Notification;
use Illuminate\Support\Facades\Cache;
use App\Traits\CommonFunction;

class MainController extends Controller
{
    use CommonFunction;

    // メイン画面の表示
    public function index(Work $work, WorkStory $workStory, Character $character, Music $music, AnimePilgrimage $animePilgrimage, Notification $notification)
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
        $topPopularityWorks = $this->addTypeToItem($topPopularityWorks, 'work');

        // 人気上位のあらすじを取得(毎度作品ごとに異なるためforgetを行う)
        Cache::forget('top_popular_work_stories');
        $sufficientPostsWorkStories = $workStory->fetchSufficientPostNumWorkStories();
        // updateTopPopularityItemsを実行して人気度の高い作品を再計算
        $workStory->updateTopPopularityItems($sufficientPostsWorkStories, 'workStoryPosts', 'top_popular_work_stories');
        // キャッシュから人気度の高い作品を取得
        $topPopularityWorkStories = Cache::get('top_popular_work_stories');
        $topPopularityWorkStories = $this->addTypeToItem($topPopularityWorkStories, 'work_story');

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
        $topPopularityCharacters = $this->addTypeToItem($topPopularityCharacters, 'character');

        // 人気上位の音楽を取得
        $topPopularityMusic = Cache::get('top_popular_music');
        // キャッシュが見つからない場合
        if (!$topPopularityMusic) {
            $sufficientPostsMusic = $music->fetchSufficientPostNumMusic();
            // updateTopPopularityItemsを実行して人気度の高い音楽を再計算
            $music->updateTopPopularityItems($sufficientPostsMusic, 'musicPosts', 'top_popular_music');
            // 再度キャッシュから人気度の高い音楽を取得
            $topPopularityMusic = Cache::get('top_popular_music');
        }
        $topPopularityMusic = $this->addTypeToItem($topPopularityMusic, 'music');

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
        $topPopularityPilgrimages = $this->addTypeToItem($topPopularityPilgrimages, 'pilgrimage');

        // 最新のお知らせ4件の取得
        $notifications = $notification->getRecentNotifications();
        return view('main.index', compact(
            'notifications',
            'topPopularityWorks',
            'topPopularityWorkStories',
            'topPopularityCharacters',
            'topPopularityMusic',
            'topPopularityPilgrimages'
        ));
    }
}
