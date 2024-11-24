<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\WorkReviewController;
use App\Http\Controllers\WorkReviewLikeController;
use App\Http\Controllers\CreatorController;
use App\Http\Controllers\CharacterController;
use App\Http\Controllers\CharacterPostController;
use App\Http\Controllers\CharacterPostLikeController;
use App\Http\Controllers\VoiceArtistController;
use App\Http\Controllers\MusicController;
use App\Http\Controllers\MusicPostController;
use App\Http\Controllers\MusicPostLikeController;
use App\Http\Controllers\SingerController;
use App\Http\Controllers\LyricWriterController;
use App\Http\Controllers\ComposerController;
use App\Http\Controllers\AnimePilgrimageController;
use App\Http\Controllers\AnimePilgrimagePostController;
use App\Http\Controllers\AnimePilgrimagePostLikeController;
use App\Http\Controllers\WorkStoryController;
use App\Http\Controllers\WorkStoryPostController;
use App\Http\Controllers\WorkStoryPostLikeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // ユーザー情報の表示
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    // ユーザー情報の編集
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    // ユーザー情報の更新処理
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // パスワードの更新画面の表示
    Route::get('/profile/password', [ProfileController::class, 'editPassword'])->name('profile.password');
    // パスワードの更新処理
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    // アカウント削除画面の表示
    Route::get('/profile/delete', [ProfileController::class, 'confirmDelete'])->name('profile.delete');
    // アカウント削除画面の表示
    Route::delete('/profile/delete', [ProfileController::class, 'delete'])->name('profile.delete.confirm');
    // 保険
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// WorkControllerに関するルーティング
Route::controller(WorkController::class)->middleware(['auth'])->group(function () {
    // 作品一覧の表示
    Route::get('/works', 'index')->name('works.index');
    // 各作品の詳細表示
    Route::get('/works/{work}', 'show')->name('works.show');
});

// WorkReviewControllerに関するルーティング
Route::controller(WorkReviewController::class)->middleware(['auth'])->group(function () {
    // 各作品ごとの感想投稿一覧の表示
    Route::get('/work_reviews/{work_id}', 'index')->name('work_reviews.index');
    // 新規投稿作成ボタン押下で、createメソッドを実行
    Route::get('/work_reviews/{work_id}/create', 'create')->name('work_reviews.create');
    // 作成するボタン押下で、storeメソッドを実行
    Route::post('/work_reviews/{work_id}/store', 'store')->name('work_reviews.store');
    // 各作品の感想投稿一覧ボタン押下で、showメソッドを実行
    Route::get('/work_reviews/{work_id}/reviews/{work_review_id}', 'show')->name('work_reviews.show');
    // 感想投稿編集画面を表示するeditメソッドを実行
    Route::get('/work_reviews/{work_id}/reviews/{work_review_id}/edit', 'edit')->name('work_reviews.edit');
    // 感想投稿の編集を実行するupdateメソッドを実行
    Route::put('/work_reviews/{work_id}/update/{work_review_id}', 'update')->name('work_reviews.update');
    // 感想投稿の削除を行うdeleteメソッドを実行
    Route::delete('/work_reviews/{work_id}/reviews/{work_review_id}/delete', 'delete')->name('work_reviews.delete');
    // 感想投稿のいいねボタン押下で、いいねを追加するlikeメソッドを実行
    Route::post('/work_reviews/{work_id}/reviews/{work_review_id}/like', 'like')->name('work_reviews.like');
});

// WorkReviewLikeControllerに関するルーティング
Route::controller(WorkReviewLikeController::class)->middleware(['auth'])->group(function () {
    // 作品一覧の表示
    Route::get('/work_reviews/{work_id}/reviews/{work_review_id}/like/index', 'index')->name('work_review_like.index');
});

// CreatorControllerに関するルーティング
Route::controller(CreatorController::class)->middleware(['auth'])->group(function () {
    // 制作会社の詳細表示
    Route::get('/creator/{creator_id}', 'show')->name('creator.show');
});

// CharacterControllerに関するルーティング
Route::controller(CharacterController::class)->middleware(['auth'])->group(function () {
    // 登場人物一覧の表示
    Route::get('/characters', 'index')->name('characters.index');
    // 登場人物の詳細表示
    Route::get('/characters/{character_id}', 'show')->name('characters.show');
});

// CharacterPostControllerに関するルーティング
Route::controller(CharacterPostController::class)->middleware(['auth'])->group(function () {
    // 登場人物ごとの感想投稿一覧の表示
    Route::get('/character_posts/{character_id}', 'index')->name('character_posts.index');
    // 新規投稿作成ボタン押下で、createメソッドを実行
    Route::get('/character_posts/{character_id}/create', 'create')->name('character_posts.create');
    // 作成するボタン押下で、storeメソッドを実行
    Route::post('/character_posts/{character_id}/store', 'store')->name('character_posts.store');
    // 各登場人物の感想投稿一覧ボタン押下で、showメソッドを実行
    Route::get('/character_posts/{character_id}/posts/{character_post_id}', 'show')->name('character_posts.show');
    // 感想投稿編集画面を表示するeditメソッドを実行
    Route::get('/character_posts/{character_id}/posts/{character_post_id}/edit', 'edit')->name('character_posts.edit');
    // 感想投稿の編集を実行するupdateメソッドを実行
    Route::put('/character_posts/{character_id}/update/{character_post_id}', 'update')->name('character_posts.update');
    // 感想投稿の削除を行うdeleteメソッドを実行
    Route::delete('/character_posts/{character_id}/posts/{character_post_id}/delete', 'delete')->name('character_posts.delete');
    // 感想投稿のいいねボタン押下で、いいねを追加するlikeメソッドを実行
    Route::post('/character_posts/{character_id}/posts/{character_post_id}/like', 'like')->name('character_posts.like');
});

// CharacterPostLikeControllerに関するルーティング
Route::controller(CharacterPostLikeController::class)->middleware(['auth'])->group(function () {
    // 作品一覧の表示
    Route::get('/character_posts/{character_id}/posts/{character_post_id}/like/index', 'index')->name('character_post_like.index');
});

// VoiceArtistControllerに関するルーティング
Route::controller(VoiceArtistController::class)->middleware(['auth'])->group(function () {
    // 声優の詳細表示
    Route::get('/voice_artist/{voice_artist_id}', 'show')->name('voice_artist.show');
});

// MusicControllerに関するルーティング
Route::controller(MusicController::class)->middleware(['auth'])->group(function () {
    // 音楽一覧の表示
    Route::get('/music', 'index')->name('music.index');
    // 音楽の詳細表示
    Route::get('/music/{music_id}', 'show')->name('music.show');
});

// MusicPostControllerに関するルーティング
Route::controller(MusicPostController::class)->middleware(['auth'])->group(function () {
    // 音楽人物ごとの感想投稿一覧の表示
    Route::get('/music_posts/{music_id}', 'index')->name('music_posts.index');
    // 新規投稿作成ボタン押下で、createメソッドを実行
    Route::get('/music_posts/{music_id}/create', 'create')->name('music_posts.create');
    // 作成するボタン押下で、storeメソッドを実行
    Route::post('/music_posts/{music_id}/store', 'store')->name('music_posts.store');
    // 各登場人物の感想投稿一覧ボタン押下で、showメソッドを実行
    Route::get('/music_posts/{music_id}/posts/{music_post_id}', 'show')->name('music_posts.show');
    // 感想投稿編集画面を表示するeditメソッドを実行
    Route::get('/music_posts/{music_id}/posts/{music_post_id}/edit', 'edit')->name('music_posts.edit');
    // 感想投稿の編集を実行するupdateメソッドを実行
    Route::put('/music_posts/{music_id}/update/{music_post_id}', 'update')->name('music_posts.update');
    // 感想投稿の削除を行うdeleteメソッドを実行
    Route::delete('/music_posts/{music_id}/posts/{music_post_id}/delete', 'delete')->name('music_posts.delete');
    // 感想投稿のいいねボタン押下で、いいねを追加するlikeメソッドを実行
    Route::post('/music_posts/{music_id}/posts/{music_post_id}/like', 'like')->name('music_posts.like');
});

// MusicPostLikeControllerに関するルーティング
Route::controller(MusicPostLikeController::class)->middleware(['auth'])->group(function () {
    // 作品一覧の表示
    Route::get('/music_posts/{music_id}/posts/{music_post_id}/like/index', 'index')->name('music_post_like.index');
});

// SingerControllerに関するルーティング
Route::controller(SingerController::class)->middleware(['auth'])->group(function () {
    // 歌手の詳細表示
    Route::get('/singer/{singer_id}', 'show')->name('singer.show');
});

// LyricWriterControllerに関するルーティング
Route::controller(LyricWriterController::class)->middleware(['auth'])->group(function () {
    // 作詞者の詳細表示
    Route::get('/lyric_writer/{lyric_writer_id}', 'show')->name('lyric_writer.show');
});

// ComposerControllerに関するルーティング
Route::controller(ComposerController::class)->middleware(['auth'])->group(function () {
    // 作曲者の詳細表示
    Route::get('/composer/{composer_id}', 'show')->name('composer.show');
});

// AnimePilgrimageControllerに関するルーティング
Route::controller(AnimePilgrimageController::class)->middleware(['auth'])->group(function () {
    // 聖地一覧の表示
    Route::get('/pilgrimages', 'index')->name('pilgrimages.index');
    // 聖地の詳細表示
    Route::get('/pilgrimages/{pilgrimage_id}', 'show')->name('pilgrimages.show');
});

// AnimePilgrimagePostControllerに関するルーティング
Route::controller(AnimePilgrimagePostController::class)->middleware(['auth'])->group(function () {
    // 聖地ごとの感想投稿一覧の表示
    Route::get('/pilgrimage_posts/{pilgrimage_id}', 'index')->name('pilgrimage_posts.index');
    // 新規投稿作成ボタン押下で、createメソッドを実行
    Route::get('/pilgrimage_posts/{pilgrimage_id}/create', 'create')->name('pilgrimage_posts.create');
    // 作成するボタン押下で、storeメソッドを実行
    Route::post('/pilgrimage_posts/{pilgrimage_id}/store', 'store')->name('pilgrimage_posts.store');
    // 各聖地の感想投稿一覧ボタン押下で、showメソッドを実行
    Route::get('/pilgrimage_posts/{pilgrimage_id}/posts/{pilgrimage_post_id}', 'show')->name('pilgrimage_posts.show');
    // 感想投稿編集画面を表示するeditメソッドを実行
    Route::get('/pilgrimage_posts/{pilgrimage_id}/posts/{pilgrimage_post_id}/edit', 'edit')->name('pilgrimage_posts.edit');
    // 感想投稿の編集を実行するupdateメソッドを実行
    Route::put('/pilgrimage_posts/{pilgrimage_id}/update/{pilgrimage_post_id}', 'update')->name('pilgrimage_posts.update');
    // 感想投稿の削除を行うdeleteメソッドを実行
    Route::delete('/pilgrimage_posts/{pilgrimage_id}/posts/{pilgrimage_post_id}/delete', 'delete')->name('pilgrimage_posts.delete');
    // 感想投稿のいいねボタン押下で、いいねを追加するlikeメソッドを実行
    Route::post('/pilgrimage_posts/{pilgrimage_id}/posts/{pilgrimage_post_id}/like', 'like')->name('pilgrimage_posts.like');
});

// AnimePilgrimagePostLikeControllerに関するルーティング
Route::controller(AnimePilgrimagePostLikeController::class)->middleware(['auth'])->group(function () {
    // 作品一覧の表示
    Route::get('/pilgrimage_posts/{pilgrimage_id}/posts/{pilgrimage_post_id}/like/index', 'index')->name('pilgrimage_post_like.index');
});

// WorkStoryControllerに関するルーティング
Route::controller(WorkStoryController::class)->middleware(['auth'])->group(function () {
    // あらすじ一覧の表示
    Route::get('/works/{work_id}/stories', 'index')->name('work_stories.index');
    // あらすじの詳細表示
    Route::get('/works/{work_id}/stories/{work_story_id}', 'show')->name('work_stories.show');
});

// WorkStoryPostControllerに関するルーティング
Route::controller(WorkStoryPostController::class)->middleware(['auth'])->group(function () {
    // あらすじごとの感想投稿一覧の表示
    Route::get('/works/{work_id}/stories/{work_story_id}/posts', 'index')->name('work_story_posts.index');
    // 新規投稿作成ボタン押下で、createメソッドを実行
    Route::get('/works/{work_id}/stories/{work_story_id}/create', 'create')->name('work_story_posts.create');
    // 作成するボタン押下で、storeメソッドを実行
    Route::post('/works/{work_id}/stories/{work_story_id}/posts/{work_story_post_id}/store', 'store')->name('work_story_posts.store');
    // 各あらすじの感想投稿一覧ボタン押下で、showメソッドを実行
    Route::get('/works/{work_id}/stories/{work_story_id}/posts/{work_story_post_id}', 'show')->name('work_story_posts.show');
    // 感想投稿編集画面を表示するeditメソッドを実行
    Route::get('/works/{work_id}/stories/{work_story_id}/posts/{work_story_post_id}/edit', 'edit')->name('work_story_posts.edit');
    // 感想投稿の編集を実行するupdateメソッドを実行
    Route::put('/works/{work_id}/stories/{work_story_id}/posts/{work_story_post_id}/update', 'update')->name('work_story_posts.update');
    // 感想投稿の削除を行うdeleteメソッドを実行
    Route::delete('/works/{work_id}/stories/{work_story_id}/posts/{work_story_post_id}/delete', 'delete')->name('work_story_posts.delete');
    // 感想投稿のいいねボタン押下で、いいねを追加するlikeメソッドを実行
    Route::post('/works/{work_id}/stories/{work_story_id}/posts/{work_story_post_id}/like', 'like')->name('work_story_posts.like');
});

// WorkStoryPostLikeControllerに関するルーティング
Route::controller(WorkStoryPostLikeController::class)->middleware(['auth'])->group(function () {
    // いいねしたユーザーの表示
    Route::get('/works/{work_id}/stories/{work_story_id}/posts/{work_story_post_id}/like/index', 'index')->name('work_story_post_like.index');
});

require __DIR__ . '/auth.php';
