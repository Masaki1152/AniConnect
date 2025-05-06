<?php

use App\Http\Controllers\User\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Main\MainController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\AdminWorkController;
use App\Http\Controllers\Work\WorkController;
use App\Http\Controllers\Work\WorkInterestedController;
use App\Http\Controllers\Work\WorkReviewController;
use App\Http\Controllers\Work\WorkReviewLikeController;
use App\Http\Controllers\RelatedParty\CreatorController;
use App\Http\Controllers\Character\CharacterController;
use App\Http\Controllers\Character\CharacterInterestedController;
use App\Http\Controllers\Character\CharacterPostController;
use App\Http\Controllers\Character\CharacterPostLikeController;
use App\Http\Controllers\RelatedParty\VoiceArtistController;
use App\Http\Controllers\Music\MusicController;
use App\Http\Controllers\Music\MusicInterestedController;
use App\Http\Controllers\Music\MusicPostController;
use App\Http\Controllers\Music\MusicPostLikeController;
use App\Http\Controllers\RelatedParty\SingerController;
use App\Http\Controllers\RelatedParty\LyricWriterController;
use App\Http\Controllers\RelatedParty\ComposerController;
use App\Http\Controllers\AnimePilgrimage\AnimePilgrimageController;
use App\Http\Controllers\AnimePilgrimage\AnimePilgrimageInterestedController;
use App\Http\Controllers\AnimePilgrimage\AnimePilgrimagePostController;
use App\Http\Controllers\AnimePilgrimage\AnimePilgrimagePostLikeController;
use App\Http\Controllers\WorkStory\WorkStoryController;
use App\Http\Controllers\WorkStory\WorkStoryInterestedController;
use App\Http\Controllers\WorkStory\WorkStoryPostController;
use App\Http\Controllers\WorkStory\WorkStoryPostLikeController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserFollowController;
use App\Http\Controllers\Notification\NotificationController;
use App\Http\Controllers\Notification\NotificationLikeController;
use App\Http\Controllers\Work\Comment\WrCommentController;
use App\Http\Controllers\Work\Comment\WrCommentLikeController;
use App\Http\Controllers\WorkStory\Comment\WspCommentController;
use App\Http\Controllers\WorkStory\Comment\WspCommentLikeController;
use App\Http\Controllers\Music\Comment\MpCommentController;
use App\Http\Controllers\Music\Comment\MpCommentLikeController;
use App\Http\Controllers\Character\Comment\CpCommentController;
use App\Http\Controllers\Character\Comment\CpCommentLikeController;
use App\Http\Controllers\AnimePilgrimage\Comment\AppCommentController;
use App\Http\Controllers\AnimePilgrimage\Comment\AppCommentLikeController;
use App\Http\Controllers\Notification\Comment\NotificationCommentController;
use App\Http\Controllers\Notification\Comment\NotificationCommentLikeController;

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

// 管理者用ルートグループ
Route::prefix('admin')
    ->middleware(['auth', 'is_admin']) // 認証済みかつ管理者のみ
    ->name('admin.')
    ->group(function () {
        // 管理画面トップ
        Route::get('dashboard', [AdminController::class, 'index'])->name('dashboard');

        // お知らせ一覧
        Route::get('notification', [AdminNotificationController::class, 'index'])->name('notifications.index');
        // お知らせ作成画面表示
        Route::get('notification/create', [AdminNotificationController::class, 'create'])->name('notifications.create');
        // お知らせ作成
        Route::post('notification/store', [AdminNotificationController::class, 'store'])->name('notifications.store');
        // お知らせ詳細表示
        Route::get('notification/{notification_id}', [AdminNotificationController::class, 'show'])->name('notifications.show');
        // お知らせ編集表示
        Route::get('notification/edit/{notification_id}', [AdminNotificationController::class, 'edit'])->name('notifications.edit');
        // お知らせ編集
        Route::put('notification/update/{notification_id}', [AdminNotificationController::class, 'update'])->name('notifications.update');
        // お知らせ削除
        Route::delete('notification/delete/{notification_id}', [AdminNotificationController::class, 'delete'])->name('notifications.delete');
        // お知らせのいいねボタン押下で、いいねを追加するlikeメソッドを実行
        Route::post('notification/like/{notification_id}', [AdminNotificationController::class, 'like'])->name('notifications.like');

        // 作品
        Route::prefix('work')
            ->name('works.')
            ->controller(AdminWorkController::class)
            ->group(function () {
                // 作品一覧の表示
                Route::get('/', 'index')->name('index');
                // 新規登録ボタン押下で、createメソッドを実行
                Route::get('create', 'create')->name('create');
                // // 登録前に内容を確認するconfirmメソッドを実行
                // Route::post('confirm', 'confirm')->name('confirm');
                // 登録ボタン押下で、storeメソッドを実行
                Route::post('store', 'store')->name('store');
                // 各作品の詳細表示
                Route::get('{work_id}', 'show')->name('show');
                // // 作品の編集画面の表示
                // Route::get('edit/{work_id}', 'edit')->name('edit');
                // // 作品の編集を保存
                // Route::put('update/{work_id}', 'update')->name('update');
                // // 作品を削除する
                // Route::delete('delete/{work_id}', 'delete')->name('delete');
            });
    });

// NotificationControllerに関するルーティング
Route::controller(NotificationCommentController::class)->middleware(['auth'])->group(function () {
    // 作成するボタン押下で、storeメソッドを実行
    Route::post('/notifications/comments/store', 'store')->name('notification.comments.store');
    // コメントの削除を行うdeleteメソッドを実行
    Route::delete('/notifications/comments/{comment_id}/delete', 'delete')->name('notification.comments.delete');
    // コメントのいいねボタン押下で、いいねを追加するlikeメソッドを実行
    Route::post('/notifications/comments/{comment_id}/like', 'like')->name('notification.comments.like');
    // ネスト化したコメントを表示するreplyメソッドを実行
    Route::get('/notifications/comments/{comment_id}/replies', 'replies')->name('notification.comments.replies');
});

// NotificationCommentLikeControllerに関するルーティング
Route::controller(NotificationCommentLikeController::class)->middleware(['auth'])->group(function () {
    // いいねしたユーザーの表示
    Route::get('/notifications/comments/{comment_id}/like/index', 'index')->name('notification_comment.like.index');
});

// NotificationControllerに関するルーティング
Route::controller(NotificationController::class)->middleware(['auth'])->group(function () {
    // お知らせ一覧の表示
    Route::get('/notification', 'index')->name('notifications.index');
    // お知らせの詳細表示
    Route::get('/notification/{notification_id}', 'show')->name('notifications.show');
    // お知らせのいいねボタン押下で、いいねを追加するlikeメソッドを実行
    Route::post('/notification/like/{notification_id}', 'like')->name('notifications.like');
});

// NotificationLikeControllerに関するルーティング
Route::controller(NotificationLikeController::class)->middleware(['auth'])->group(function () {
    // お知らせのいいね一覧の表示
    Route::get('/notification/like/{notification_id}/index', 'index')->name('notification_like.index');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// 誰でも閲覧できるメイン画面の表示
// MainControllerに関するルーティング
Route::controller(MainController::class)->middleware(['auth'])->group(function () {
    // 一覧の表示
    Route::get('/main', 'index')->name('main.index');
});

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
    // パスワードの削除処理
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// WorkControllerに関するルーティング
Route::controller(WorkController::class)->middleware(['auth'])->group(function () {
    // 作品一覧の表示
    Route::get('/works', 'index')->name('works.index');
    // 各作品の詳細表示
    Route::get('/works/{work}', 'show')->name('works.show');
    // 各作品の「気になる」ボタン押下で「気になる」登録をする処理
    Route::post('/works/{work_id}/interested', 'interested')->name('works.interested');
});

// WorkInterestedControllerに関するルーティング
Route::controller(WorkInterestedController::class)->middleware(['auth'])->group(function () {
    // 各作品を気になる登録したユーザーの表示
    Route::get('/works/{work_id}/interested/index', 'index')->name('work.interested.index');
});

// WrCommentControllerに関するルーティング
Route::controller(WrCommentController::class)->middleware(['auth'])->group(function () {
    // 作成するボタン押下で、storeメソッドを実行
    Route::post('/work_reviews/comments/store', 'store')->name('work_review.comments.store');
    // コメントの削除を行うdeleteメソッドを実行
    Route::delete('/work_reviews/comments/{comment_id}/delete', 'delete')->name('work_review.comments.delete');
    // コメントのいいねボタン押下で、いいねを追加するlikeメソッドを実行
    Route::post('/work_reviews/comments/{comment_id}/like', 'like')->name('work_review.comments.like');
    // ネスト化したコメントを表示するreplyメソッドを実行
    Route::get('/work_reviews/comments/{comment_id}/replies', 'replies')->name('work_review.comments.replies');
});

// WorkReviewCommentLikeControllerに関するルーティング
Route::controller(WrCommentLikeController::class)->middleware(['auth'])->group(function () {
    // 作品一覧の表示
    Route::get('/work_reviews/comments/{comment_id}/like/index', 'index')->name('work_review_comment.like.index');
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
    // いいね一覧の表示
    Route::get('/work_reviews/{work_id}/reviews/{work_review_id}/like/index', 'index')->name('work_review_like.index');
});

// CreatorControllerに関するルーティング
Route::controller(CreatorController::class)->middleware(['auth'])->group(function () {
    // 制作会社の検索
    Route::get('/creator/search', 'search')->name('creator.search');
    // 制作会社の詳細表示
    Route::get('/creator/{creator_id}', 'show')->name('creator.show');
});

// CharacterControllerに関するルーティング
Route::controller(CharacterController::class)->middleware(['auth'])->group(function () {
    // 登場人物一覧の表示
    Route::get('/characters', 'index')->name('characters.index');
    // 登場人物の詳細表示
    Route::get('/characters/{character_id}', 'show')->name('characters.show');
    // 各登場人物の「気になる」ボタン押下で「気になる」登録をする処理
    Route::post('/characters/{character_id}/interested', 'interested')->name('characters.interested');
});

// CharacterInterestedControllerに関するルーティング
Route::controller(CharacterInterestedController::class)->middleware(['auth'])->group(function () {
    // 各登場人物を気になる登録したユーザーの表示
    Route::get('/characters/{character_id}/interested/index', 'index')->name('characters.interested.index');
});

// CpCommentControllerに関するルーティング
Route::controller(CpCommentController::class)->middleware(['auth'])->group(function () {
    // 作成するボタン押下で、storeメソッドを実行
    Route::post('/character_posts/comments/store', 'store')->name('character_post.comments.store');
    // コメントの削除を行うdeleteメソッドを実行
    Route::delete('/character_posts/comments/{comment_id}/delete', 'delete')->name('character_post.comments.delete');
    // コメントのいいねボタン押下で、いいねを追加するlikeメソッドを実行
    Route::post('/character_posts/comments/{comment_id}/like', 'like')->name('character_post.comments.like');
    // ネスト化したコメントを表示するreplyメソッドを実行
    Route::get('/character_posts/comments/{comment_id}/replies', 'replies')->name('character_post.comments.replies');
});

// CpCommentLikeControllerに関するルーティング
Route::controller(CpCommentLikeController::class)->middleware(['auth'])->group(function () {
    // いいねしたユーザーの表示
    Route::get('/character_posts/comments/{comment_id}/like/index', 'index')->name('character_post_comment.like.index');
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
    // 音楽の「気になる」ボタン押下で「気になる」登録をする処理
    Route::post('/music/{music_id}/interested', 'interested')->name('music.interested');
});

// MusicInterestedControllerに関するルーティング
Route::controller(MusicInterestedController::class)->middleware(['auth'])->group(function () {
    // 音楽を気になる登録したユーザーの表示
    Route::get('/music/{music_id}/interested/index', 'index')->name('music.interested.index');
});

// MpCommentControllerに関するルーティング
Route::controller(MpCommentController::class)->middleware(['auth'])->group(function () {
    // 作成するボタン押下で、storeメソッドを実行
    Route::post('/music_posts/comments/store', 'store')->name('music_post.comments.store');
    // コメントの削除を行うdeleteメソッドを実行
    Route::delete('/music_posts/comments/{comment_id}/delete', 'delete')->name('music_post.comments.delete');
    // コメントのいいねボタン押下で、いいねを追加するlikeメソッドを実行
    Route::post('/music_posts/comments/{comment_id}/like', 'like')->name('music_post.comments.like');
    // ネスト化したコメントを表示するreplyメソッドを実行
    Route::get('/music_posts/comments/{comment_id}/replies', 'replies')->name('music_post.comments.replies');
});

// MpCommentLikeControllerに関するルーティング
Route::controller(MpCommentLikeController::class)->middleware(['auth'])->group(function () {
    // いいねしたユーザーの表示
    Route::get('/music_posts/comments/{comment_id}/like/index', 'index')->name('music_post_comment.like.index');
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
    // 聖地の「気になる」ボタン押下で「気になる」登録をする処理
    Route::post('/pilgrimages/{pilgrimage_id}/interested', 'interested')->name('pilgrimages.interested');
});

// AnimePilgrimageInterestedControllerに関するルーティング
Route::controller(AnimePilgrimageInterestedController::class)->middleware(['auth'])->group(function () {
    // 聖地を気になる登録したユーザーの表示
    Route::get('/pilgrimages/{pilgrimage_id}/interested/index', 'index')->name('pilgrimages.interested.index');
});

// AppCommentControllerに関するルーティング
Route::controller(AppCommentController::class)->middleware(['auth'])->group(function () {
    // 作成するボタン押下で、storeメソッドを実行
    Route::post('/pilgrimage_posts/comments/store', 'store')->name('pilgrimage_post.comments.store');
    // コメントの削除を行うdeleteメソッドを実行
    Route::delete('/pilgrimage_posts/comments/{comment_id}/delete', 'delete')->name('pilgrimage_post.comments.delete');
    // コメントのいいねボタン押下で、いいねを追加するlikeメソッドを実行
    Route::post('/pilgrimage_posts/comments/{comment_id}/like', 'like')->name('pilgrimage_post.comments.like');
    // ネスト化したコメントを表示するreplyメソッドを実行
    Route::get('/pilgrimage_posts/comments/{comment_id}/replies', 'replies')->name('pilgrimage_post.comments.replies');
});

// AppCommentLikeControllerに関するルーティング
Route::controller(AppCommentLikeController::class)->middleware(['auth'])->group(function () {
    // いいねしたユーザーの表示
    Route::get('/pilgrimage_posts/comments/{comment_id}/like/index', 'index')->name('pilgrimage_post_comment.like.index');
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
    // 各あらすじの「気になる」ボタン押下で「気になる」登録をする処理
    Route::post('/works/{work_id}/stories/{work_story_id}/interested', 'interested')->name('work_stories.interested');
});

// WorkStoryInterestedControllerに関するルーティング
Route::controller(WorkStoryInterestedController::class)->middleware(['auth'])->group(function () {
    // 各あらすじを気になる登録したユーザーの表示
    Route::get('/works/{work_id}/stories/{work_story_id}/interested/index', 'index')->name('work_stories.interested.index');
});

// WspCommentControllerに関するルーティング
Route::controller(WspCommentController::class)->middleware(['auth'])->group(function () {
    // 作成するボタン押下で、storeメソッドを実行
    Route::post('/work_story_posts/comments/store', 'store')->name('work_story_post.comments.store');
    // コメントの削除を行うdeleteメソッドを実行
    Route::delete('/work_story_posts/comments/{comment_id}/delete', 'delete')->name('work_story_post.comments.delete');
    // コメントのいいねボタン押下で、いいねを追加するlikeメソッドを実行
    Route::post('/work_story_posts/comments/{comment_id}/like', 'like')->name('work_story_post.comments.like');
    // ネスト化したコメントを表示するreplyメソッドを実行
    Route::get('/work_story_posts/comments/{comment_id}/replies', 'replies')->name('work_story_post.comments.replies');
});

// WspCommentLikeControllerに関するルーティング
Route::controller(WspCommentLikeController::class)->middleware(['auth'])->group(function () {
    // いいねしたユーザーの表示
    Route::get('/work_story_posts/comments/{comment_id}/like/index', 'index')->name('work_story_post_comment.like.index');
});

// WorkStoryPostControllerに関するルーティング
Route::controller(WorkStoryPostController::class)->middleware(['auth'])->group(function () {
    // あらすじごとの感想投稿一覧の表示
    Route::get('/works/{work_id}/stories/{work_story_id}/posts', 'index')->name('work_story_posts.index');
    // 新規投稿作成ボタン押下で、createメソッドを実行
    Route::get('/works/{work_id}/stories/{work_story_id}/create', 'create')->name('work_story_posts.create');
    // 作成するボタン押下で、storeメソッドを実行
    Route::post('/works/{work_id}/stories/{work_story_id}/posts/store', 'store')->name('work_story_posts.store');
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

// UserControllerに関するルーティング
Route::controller(UserController::class)->middleware(['auth'])->group(function () {
    // ユーザー一覧の表示
    Route::get('/users', 'index')->name('users.index');
    // ユーザーの詳細表示
    Route::get('/users/{user_id}', 'show')->name('users.show');
    // ユーザーをフォローするメソッドを実行
    Route::post('/users/{user_id}/follow', 'follow')->name('users.follow');
    // ユーザーの投稿を表示するメソッドを実行
    Route::post('/users/{user_id}/posts/{type}', 'fetchPosts')->name('users.fetchPosts');
});

// UserFollowControllerに関するルーティング
Route::controller(UserFollowController::class)->middleware(['auth'])->group(function () {
    // フォローしたユーザーの表示
    Route::get('/users/{user_id}/following/index', 'indexFollowingUser')->name('user_follows.indexFollowingUser');
    // フォロワーの表示
    Route::get('/users/{user_id}/followed/index', 'indexFollowedUser')->name('user_follows.indexFollowedUser');
});

require __DIR__ . '/auth.php';
