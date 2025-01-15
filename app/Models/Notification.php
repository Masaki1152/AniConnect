<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    // fillを実行するための記述
    protected $fillable = [
        'title',
        'body',
        'image1',
        'image2',
        'image3',
        'image4',
    ];

    // 参照させたいnotificationsを指定
    protected $table = 'notifications';

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // お知らせの検索処理
    public function fetchNotifications($search, $categoryIds)
    {
        // 指定したidのお知らせのみを表示
        $notifications = Notification::with(['categories'])
            ->where(function ($query) use ($search, $categoryIds) {
                // キーワード検索がなされた場合
                if ($search) {
                    // 検索語のスペースを半角に統一
                    $search_split = mb_convert_kana($search, 's');
                    // 半角スペースで単語ごとに分割して配列にする
                    $search_array = preg_split('/[\s]+/', $search_split);
                    foreach ($search_array as $search_word) {
                        // 自身のカラムでの検索
                        $query->where(function ($query) use ($search_word) {
                            $query->where('title', 'LIKE', "%{$search_word}%")
                                ->orWhere('body', 'LIKE', "%{$search_word}%");
                        });
                    }
                }

                // クリックされたカテゴリーIdがある場合
                if (!empty($categoryIds)) {
                    foreach ($categoryIds as $categoryId) {
                        $query->whereHas('categories', function ($categoryQuery) use ($categoryId) {
                            $categoryQuery->where('notification_categories.id', $categoryId);
                        });
                    }
                }
            })
            ->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->paginate(5);
        return $notifications;
    }

    // メイン画面の新着のお知らせ5件を取得する
    public function getRecentNotifications()
    {
        $recentNotifications = Notification::orderBy('created_at', 'desc')
            ->orderBy('id', 'DESC')
            ->take(5)
            ->get();
        return $recentNotifications;
    }

    // いいねをしたUserに対するリレーション　多対多の関係
    public function users()
    {
        return $this->belongsToMany(User::class, 'notification_user', 'notification_id', 'user_id');
    }

    // カテゴリーに対するリレーション 多対多の関係
    public function categories()
    {
        return $this->belongsToMany(NotificationCategory::class, 'notification_category', 'notification_id', 'notification_category_id');
    }

    // コメントに対するリレーション 一対多の関係
    public function notificationComments()
    {
        return $this->hasMany(NotificationComment::class, 'notification_id', 'id');
    }
}
