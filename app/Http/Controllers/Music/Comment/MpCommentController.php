<?php

namespace App\Http\Controllers\Music\Comment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\MpCommentRequest;
use App\Models\MusicPostComment;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Traits\CommonFunction;

class MpCommentController extends Controller
{
    use SoftDeletes;
    use CommonFunction;

    // 新しく記述した内容を保存する
    public function store(MusicPostComment $mp_comment, MpCommentRequest $request)
    {
        $input_comment = $request['music_post_comment'];
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
        $mp_comment->fill($input_comment)->save();

        // statusの確認
        $status = is_null($mp_comment->parent_id) ? 'comment_stored' : 'child_comment_stored';

        // Bladeテンプレートをレンダリング
        $commentHtml = view('user_interactions.comments.input_comment', [
            'comment' => $mp_comment,
            'status' => $status,
            'inputName' => 'music_post_comment',
            'baseRoute' => 'music_post',
            'inputPostIdName' => 'music_post_id',
            'postCommentId' => $mp_comment->music_post_id,
            'parentId' => $mp_comment->parent_id
        ])->render();
        $message = __('messages.comment_posted');
        return response()->json(['message' => $message, 'new_comment_id' => $mp_comment->id, 'commentHtml' => $commentHtml]);
    }

    // コメントを削除する
    public function delete(MusicPostComment $mp_comment, $comment_id)
    {
        try {
            $parentCommentArray = $mp_comment->getParentCommentArray($comment_id);
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
        $comment = MusicPostComment::find($comment_id);
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
        $count = count($comment->users()->pluck('mp_comment_id')->toArray());

        return response()->json(['status' => $status, 'like_user' => $count, 'message' => $message]);
    }

    // ネスト化したコメントの表示
    public function replies(MusicPostComment $mp_comment, $comment_id)
    {
        $mp_comment = MusicPostComment::find($comment_id);
        $replies = $mp_comment->replies()->with('user', 'users', 'replies')->get();
        $replies = $replies->map(function ($reply) {
            // Bladeテンプレートをレンダリング
            $reply->html = view('user_interactions.comments.input_comment', [
                'comment' => $reply,
                'status' => 'show',
                'inputName' => 'music_post_comment',
                'baseRoute' => 'music_post',
                'inputPostIdName' => 'music_post_id',
                'postCommentId' => $reply->music_post_id,
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
        $targetComment = MusicPostComment::find($comment_id);
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
