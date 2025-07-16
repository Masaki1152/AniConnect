<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //ペジネーションの使用
        Paginator::useBootstrap();

        // カテゴリーの色を共有する
        View::composer('*', function ($view) {
            $view->with('categoryColors', [
                // 「気になる」登録
                __('messages.marked_as_interested') => getCategoryColor(__('messages.marked_as_interested')),
                __('messages.unmarked_as_interested') => getCategoryColor(__('messages.unmarked_as_interested')),
                // 画像のトリミング
                __('messages.image_cropped') => getCategoryColor(__('messages.image_cropped')),
                __('messages.all_images_cropped') => getCategoryColor(__('messages.all_images_cropped')),
                // コメント
                __('messages.all_related_replies_deleted') => getCategoryColor(__('messages.all_related_replies_deleted')),
                __('messages.failed_to_delete_comment') => getCategoryColor(__('messages.failed_to_delete_comment')),
                __('messages.comment_posted') => getCategoryColor(__('messages.comment_posted')),
                __('messages.liked') => getCategoryColor(__('messages.liked')),
                __('messages.unliked') => getCategoryColor(__('messages.unliked')),
            ]);
        });
    }
}
