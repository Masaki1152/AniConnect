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

    // 各投稿数と星の数から平均を取得
    public function updateAverageStarNum($items, $sufficientReviewsItems, $relation)
    {

        $itemEvaluations = [];

        foreach ($sufficientReviewsItems as $sufficientReviewsItem) {
            $totalScore = 0;

            foreach ($sufficientReviewsItem->$relation as $post) {
                // 各投稿の評価を合計
                $totalScore += $post->star_num;
            }
            // 投稿数が多いほど信頼性が上がるよう補正
            $numReviews = $sufficientReviewsItem->$relation->count();
            $averageEvaluation = round($totalScore / $numReviews, 1);
            $itemEvaluations[$sufficientReviewsItem->id] = ['evaluation' => $averageEvaluation, 'count' => $numReviews];
        }

        // 各アイテムの平均評価をテーブルに反映
        foreach ($items as $item) {
            $average_star_num = $itemEvaluations[$item->id]['evaluation'] ?? 9.9;
            $item->average_star_num = $average_star_num;
            $item->save();
        }
    }

    // 各アイテムの投稿数を取得
    public function countPosts($items, $relation)
    {
        foreach ($items as $item) {
            $item->post_num = $item->$relation->count() ?? 0;
        }
        return $items;
    }
}
