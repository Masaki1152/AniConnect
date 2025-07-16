<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationComment extends Model
{
    use HasFactory;
    use SerializeDate;

    // fillを実行するための記述
    protected $fillable = [
        'notification_id',
        'parent_id',
        'user_id',
        'body',
        'image1',
        'image2',
        'image3',
        'image4',
    ];

    // 参照させたいnotification_commentsを指定
    protected $table = 'notification_comments';

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // お知らせとのリレーション 多対一の関係
    public function notification()
    {
        return $this->belongsTo(Notification::class, 'notification_id', 'id');
    }

    // Userに対するリレーション 1対1の関係
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // 親コメントとのリレーション 多対一の関係
    public function parent()
    {
        return $this->belongsTo(NotificationComment::class, 'parent_id', 'id');
    }

    // 子コメントとのリレーション 一対多の関係
    public function replies()
    {
        return $this->hasMany(NotificationComment::class, 'parent_id', 'id');
    }

    // いいねをしたUserに対するリレーション　多対多の関係
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_notification_comment', 'notification_comment_id', 'user_id');
    }

    // 該当の親コメントを持つコメントを取得
    public function getParentCommentArray($comment_id)
    {
        $parentCommentArray = collect([$comment_id]);
        // 再起処理用のキュー
        $queue = [$comment_id];

        while (!empty($queue)) {
            $currentId = array_shift($queue);
            $childComments = $this->where('parent_id', $currentId)->pluck('id');
            $parentCommentArray = $parentCommentArray->merge($childComments);
            // キューの更新
            $queue = array_merge($queue, $childComments->toArray());
        }

        return $parentCommentArray;
    }
}
