<?php

use Illuminate\Support\Facades\Lang;

if (!function_exists('getCategoryColor')) {
    /**
     * カテゴリー名に基づいて色コードを返す
     *
     * @param string $categoryName
     * @return string
     */
    function getCategoryColor($categoryName)
    {
        $colors = [
            // 作品とあらすじのカテゴリー
            __('categories.cute') => '#FFB6C1',
            __('categories.interesting') => '#FFD700',
            __('categories.horror') => '#483d8b',
            __('categories.emotional') => '#4682B4',
            __('categories.uplifting') => '#FF4500',
            __('categories.battle') => '#DC143C',
            // 登場人物のカテゴリー
            __('categories.short_hair') => '#20B2AA',
            __('categories.high_school_student') => '#40E0D0',
            __('categories.blonde_hair') => '#FFD700',
            __('categories.energetic') => '#FF6347',
            __('categories.modest') => '#B0C4DE',
            // 音楽のカテゴリー
            __('categories.cool') => '#2F4F4F',
            __('categories.mellow') => '#556B2F',
            __('categories.fun') => '#FF69B4',
            __('categories.intense') => '#FF0000',
            // 聖地のカテゴリー
            __('categories.quiet') => '#708090',
            __('categories.lively') => '#FF8C00',
            __('categories.calm') => '#6B8E23',
            __('categories.exciting') => '#8B0000',
            __('categories.romantic') => '#FF69B4',
            __('categories.relaxing') => '#ADD8E6',
            // お知らせのカテゴリー
            __('categories.new_work_added') => '#4CAF50',
            __('categories.new_character_added') => '#FF9800',
            __('categories.new_music_added') => '#2196F3',
            __('categories.new_location_added') => '#9C27B0',
            __('categories.new_feature_added') => '#FFC107',
            __('categories.important_announcement') => '#F44336',
            __('categories.precautions') => '#FF5722',
            __('categories.bug_report') => '#607D8B',
            __('categories.others') => '#9E9E9E',
            // 投稿の作成後、保存後のセッションの色
            __('messages.post_edited') => '#22c55e80',
            __('messages.post_deleted') => '#ef444480',
            __('messages.new_post_created') => '#22c55e80',
            __('messages.all_related_replies_deleted') => '#ef444480',
            __('messages.failed_to_delete_comment') => '#ef444480',
            __('messages.comment_posted') => '#22c55e80',
            __('messages.liked') => '#3b82f680',
            __('messages.unliked') => '#3b82f680',
            __('messages.all_images_cropped') => '#22c55e80',
            __('messages.image_cropped') => '#22c55e80',
            // 気になる登録をした場合
            __('messages.marked_as_interested') => '#3b82f680',
            __('messages.unmarked_as_interested') => '#3b82f680',
            // エンティティの登録、編集、削除をしたとき
            __('messages.new_creator_registered') => '#22c55e80',
            __('messages.new_creator_updated') => '#22c55e80',
            __('messages.new_creator_deleted') => '#ef444480',
        ];

        // 見つからない場合はデフォルトのものを使用
        return $colors[$categoryName] ?? '#d1d5db';
    }
}
