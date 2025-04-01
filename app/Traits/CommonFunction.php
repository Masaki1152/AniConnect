<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

trait CommonFunction
{
    // 各項目の感想から星の評価と投稿数を取得、計算し、人気度を算出
    public function updateTopPopularityItems($sufficientReviewsItems, $relation, $cacheName)
    {
        // 30日で影響度が半減する設定
        $halfLife = 30;

        $popularityScores = [];

        foreach ($sufficientReviewsItems as $sufficientReviewsItem) {
            $totalScore = 0;
            $totalWeight = 0;

            foreach ($sufficientReviewsItem->$relation as $post) {
                $daysSincePost = Carbon::now()->diffInDays($post->created_at);
                // 各投稿の重み 指数関数を使って重みつけ
                $weight = exp(-$daysSincePost / $halfLife);
                $totalScore += $post->star_num * $weight;
                $totalWeight += $weight;
            }
            // 投稿数が多いほど信頼性が上がるよう補正
            $numReviews = $sufficientReviewsItem->$relation->count();
            $finalScore = ($totalWeight > 0) ? ($totalScore / $totalWeight) : 0;
            $finalScore *= log($numReviews + 1) + 1;
            $popularityScores[] = ['item' => $sufficientReviewsItem, 'score' => $finalScore];
        }

        // 人気度で並べる
        usort($popularityScores, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        // 上位3アイテムをキャッシュに保存
        Cache::put($cacheName, array_slice($popularityScores, 0, 3), now()->addDay());
    }
}
