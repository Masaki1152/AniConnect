<?php

namespace App\Http\Controllers\Notification\Comment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NotificationComment;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Traits\CommonFunction;

class NotificationCommentController extends Controller
{
    use SoftDeletes;
    use CommonFunction;

    // 新しく記述した内容を保存する
    public function store(NotificationComment $notification_comment, Request $request)
    {
        $input_comment = $request['notification_comment'];
        //cloudinaryへ画像を送信し、画像のURLを$image_urlに代入
        //画像ファイルが送られた時だけ処理が実行される
        if ($request->file('images')) {
            $counter = 1;
            foreach ($request->file('images') as $image) {
                $image_url = Cloudinary::upload($image->getRealPath(), [
                    'transformation' => [
                        'width' => 800,
                        'height' => 600,
                        'crop' => 'pad',
                        'background' => 'white',
                    ]
                ])->getSecurePath();
                $input_comment += ["image$counter" => $image_url];
                $counter++;
            }
        }
        // ログインしているユーザーidの登録
        $input_comment['user_id'] = Auth::id();
        $notification_comment->fill($input_comment)->save();

        // statusの確認
        $status = is_null($notification_comment->parent_id) ? 'comment_stored' : 'child_comment_stored';

        // Bladeテンプレートをレンダリング
        $commentHtml = view('user_interactions.comments.input_comment', [
            'comment' => $notification_comment,
            'status' => $status,
            'inputName' => 'notification_comment',
            'baseRoute' => 'notification',
            'inputPostIdName' => 'notification_id',
            'postCommentId' => $notification_comment->notification_id,
            'parentId' => $notification_comment->parent_id
        ])->render();
        $message = __('messages.comment_posted');
        return response()->json(['message' => $message, 'new_comment_id' => $notification_comment->id, 'commentHtml' => $commentHtml]);
    }

    // コメントを削除する
    public function delete(NotificationComment $notification_comment, $comment_id)
    {
        try {
            $parentCommentArray = $notification_comment->getParentCommentArray($comment_id);
            // 子コメントがあれば全て削除
            foreach ($parentCommentArray as $target_comment_id) {
                $this->deleteComment($target_comment_id);
            }
            // コメントの数を取得
            $comment_count = count($parentCommentArray);
            $message = __('messages.all_related_replies_deleted');

            return response()->json(['message' => $message, 'commentCount' => $comment_count], 200);
        } catch (\Exception $e) {
            $message = __('messages.failed_to_delete_comment');
            return response()->json(['message' => $message], 500);
        }
    }

    // コメントにいいねを行う
    public function like($comment_id)
    {
        // コメントが見つからない場合の処理
        $comment = NotificationComment::find($comment_id);
        if (!$comment) {
            $message = __('messages.comment_not_found');
            return response()->json(['message' => $message], 404);
        }
        // 現在ログインしているユーザーが既にいいねしていればtrueを返す
        $isLiked = $comment->users()->where('user_id', Auth::id())->exists();
        if ($isLiked) {
            // 既にいいねしている場合
            $comment->users()->detach(Auth::id());
            $status = 'unliked';
            $message = __('messages.unliked');
        } else {
            // 初めてのいいねの場合
            $comment->users()->attach(Auth::id());
            $status = 'liked';
            $message = __('messages.liked');
        }
        // いいねしたユーザー数の取得
        $count = count($comment->users()->pluck('notification_comment_id')->toArray());

        return response()->json(['status' => $status, 'like_user' => $count, 'message' => $message]);
    }

    // ネスト化したコメントの表示
    public function replies(NotificationComment $notification_comment, $comment_id)
    {
        $notification_comment = NotificationComment::find($comment_id);
        $replies = $notification_comment->replies()->with('user', 'users', 'replies')->get();
        $replies = $replies->map(function ($reply) {
            // Bladeテンプレートをレンダリング
            $reply->html = view('user_interactions.comments.input_comment', [
                'comment' => $reply,
                'status' => 'show',
                'inputName' => 'notification_comment',
                'baseRoute' => 'notification',
                'inputPostIdName' => 'notification_id',
                'postCommentId' => $reply->notification_id,
                'parentId' => $reply->parent_id
            ])->render();
            return $reply;
        });

        return response()->json(['replies' => $replies]);
    }

    // 該当するコメントを削除する
    public function deleteComment($comment_id)
    {
        // 編集の対象となるデータを取得
        $targetComment = NotificationComment::find($comment_id);
        // 削除する投稿の画像も削除する処理
        for ($counter = 1; $counter < 5; $counter++) {
            $removed_image_path = $targetComment->{'image' . $counter};
            // DBのimageの中身がnullであれば処理をスキップする
            if (is_null($removed_image_path)) {
                break;
            }
            $public_id = $this->extractPublicIdFromUrl($removed_image_path);
            Cloudinary::destroy($public_id);
        }
        // データの削除
        $targetComment->delete();
    }
}
