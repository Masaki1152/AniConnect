<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkReviewController;

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

/*Route::get('/', function () {
    return view('posts.work_review_index');
});*/

Route::get('/', [WorkReviewController::class, 'index']);

// 新規投稿作成ボタン押下で、createメソッドを実行
Route::get('/work_reviews/create', [WorkReviewController::class, 'create']);

// 作成するボタン押下で、storeメソッドを実行
Route::post('/work_reviews', [WorkReviewController::class, 'store']);

// '/work_reviews/{対象データのID}'にGetリクエストが来たら、showメソッドを実行
Route::get('/work_reviews/{workreview}', [WorkReviewController::class ,'show']);

// 感想投稿編集画面を表示するeditメソッドを実行
Route::get('/work_reviews/{workreview}/edit', [WorkReviewController::class, 'edit']);

// 感想投稿の編集を実行するupdateメソッドを実行
Route::put('/work_reviews/{workreview}', [WorkReviewController::class, 'update']);
