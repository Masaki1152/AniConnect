<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

trait CommonFunction
{
    // 各項目の感想から星の評価と投稿数を取得、計算し、人気度を算出
    public function updateTopPopularityItems($sufficientPostsItems, $relation, $cacheName)
    {
        // 30日で影響度が半減する設定
        $halfLife = 30;

        $popularityScores = [];

        foreach ($sufficientPostsItems as $sufficientPostsItem) {
            $totalScore = 0;
            $totalWeight = 0;

            foreach ($sufficientPostsItem->$relation as $post) {
                $daysSincePost = Carbon::now()->diffInDays($post->created_at);
                // 各投稿の重み 指数関数を使って重みつけ
                $weight = exp(-$daysSincePost / $halfLife);
                $totalScore += $post->star_num * $weight;
                $totalWeight += $weight;
            }
            // 投稿数が多いほど信頼性が上がるよう補正
            $numPosts = $sufficientPostsItem->$relation->count();
            $finalScore = ($totalWeight > 0) ? ($totalScore / $totalWeight) : 0;
            $finalScore *= log($numPosts + 1) + 1;
            $popularityScores[] = ['item' => $sufficientPostsItem, 'score' => $finalScore];
        }

        // 人気度で並べる
        usort($popularityScores, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        // 上位3アイテムをキャッシュに保存
        Cache::put($cacheName, array_slice($popularityScores, 0, 3), now()->addDay());
    }

    // 各投稿数と星の数から平均を取得
    public function updateAverageStarNum($items, $sufficientPostsItems, $relation)
    {

        $itemEvaluations = [];

        foreach ($sufficientPostsItems as $sufficientPostsItem) {
            $totalScore = 0;

            foreach ($sufficientPostsItem->$relation as $post) {
                // 各投稿の評価を合計
                $totalScore += $post->star_num;
            }
            // 投稿数が多いほど信頼性が上がるよう補正
            $numPosts = $sufficientPostsItem->$relation->count();
            $averageEvaluation = round($totalScore / $numPosts, 1);
            $itemEvaluations[$sufficientPostsItem->id] = ['evaluation' => $averageEvaluation, 'count' => $numPosts];
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

    // キーワードのみの検索処理
    public function fetchObjects($search, $Model)
    {
        $objects = $Model
            ->where(function ($query) use ($search) {
                // キーワード検索がなされた場合
                if ($search) {
                    // 検索語のスペースを半角に統一
                    $search_split = mb_convert_kana($search, 's');
                    // 半角スペースで単語ごとに分割して配列にする
                    $search_array = preg_split('/[\s]+/', $search_split);
                    foreach ($search_array as $search_word) {
                        $query->where(function ($query) use ($search_word) {
                            // 自身のカラムでの検索
                            $query->where('name', 'LIKE', "%{$search_word}%");
                        });
                    }
                }
            })
            ->orderBy('id', 'ASC')
            ->paginate(5);

        return $objects;
    }

    // Cloudinaryにある画像のURLからpublic_Idを取得する
    public function extractPublicIdFromUrl($url)
    {
        // URLの中からpublic_idを抽出するための正規表現
        $pattern = '/upload\/(?:v\d+\/)?([^\.]+)\./';

        if (preg_match($pattern, $url, $matches)) {
            // 抽出されたpublic_id
            return $matches[1];
        }
        // 該当しない場合はnull
        return null;
    }

    // 各項目の人気アイテムにタイプを渡す
    public function addTypeToItem($items, $type)
    {
        foreach ($items as &$item) {
            $item['itemType'] = $type;
        }
        return $items;
    }
}
