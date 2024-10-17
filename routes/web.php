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

// '/work_reviews/{対象データのID}'にGetリクエストが来たら、showメソッドを実行
Route::get('/work_reviews/{workreview}', [WorkReviewController::class ,'show']);
