<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\WorkReviewController;
use App\Http\Controllers\WorkReviewLikeController;
use App\Http\Controllers\CreatorController;
use App\Http\Controllers\CharacterController;
use App\Http\Controllers\CharacterPostController;
use App\Http\Controllers\VoiceArtistController;


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
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
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

// VoiceArtistControllerに関するルーティング
Route::controller(VoiceArtistController::class)->middleware(['auth'])->group(function () {
    // 声優の詳細表示
    Route::get('/voice_artist/{voice_artist_id}', 'show')->name('voice_artist.show');
});

require __DIR__ . '/auth.php';
