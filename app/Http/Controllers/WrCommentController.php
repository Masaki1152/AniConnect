<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\WrCommentRequest;
use App\Models\WorkReviewComment;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class WrCommentController extends Controller
{
    use SoftDeletes;

    // 新しく記述した内容を保存する
    public function store(WorkReviewComment $wr_comment, WrCommentRequest $request)
    {
        $input_comment = $request['work_review_comment'];
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
        $wr_comment->fill($input_comment)->save();

        // statusの確認
        $status = is_null($wr_comment->parent_id) ? 'comment_stored' : 'child_comment_stored';

        // Bladeテンプレートをレンダリング
        $commentHtml = view('comments.input_comment', ['comment' => $wr_comment, 'status' => $status])->render();
        return response()->json(['message' => 'コメントを投稿しました。', 'new_comment_id' => $wr_comment->id, 'commentHtml' => $commentHtml]);
    }

    // コメントを削除する
    public function delete(WorkReviewComment $wr_comment, $comment_id)
    {
        try {
            $parentCommentArray = $wr_comment->getParentCommentArray($comment_id);
            // 子コメントがあれば全て削除
            foreach ($parentCommentArray as $target_comment_id) {
                $this->deleteComment($target_comment_id);
            }
            // コメントの数を取得
            $cpmment_count = count($parentCommentArray);

            return response()->json(['message' => 'コメントと関連するすべての返信を削除しました', 'commentCount' => $cpmment_count], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'コメントの削除に失敗しました'], 500);
        }
    }

    // コメントにいいねを行う
    public function like($comment_id)
    {
        // コメントが見つからない場合の処理
        $comment = WorkReviewComment::find($comment_id);
        if (!$comment) {
            return response()->json(['message' => 'Post not found'], 404);
        }
        // 現在ログインしているユーザーが既にいいねしていればtrueを返す
        $isLiked = $comment->users()->where('user_id', Auth::id())->exists();
        if ($isLiked) {
            // 既にいいねしている場合
            $comment->users()->detach(Auth::id());
            $status = 'unliked';
            $message = 'いいねを解除しました';
        } else {
            // 初めてのいいねの場合
            $comment->users()->attach(Auth::id());
            $status = 'liked';
            $message = 'いいねしました';
        }
        // いいねしたユーザー数の取得
        $count = count($comment->users()->pluck('wr_comment_id')->toArray());

        return response()->json(['status' => $status, 'like_user' => $count, 'message' => $message]);
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

    // ネスト化したコメントの表示
    public function replies(WorkReviewComment $wr_comment, $comment_id)
    {
        $wr_comment = WorkReviewComment::find($comment_id);
        $replies = $wr_comment->replies()->with('user', 'users', 'replies')->get();
        $replies = $replies->map(function ($reply) {
            // Bladeテンプレートをレンダリング
            $reply->html = view('comments.input_comment', ['comment' => $reply, 'status' => 'show'])->render();
            return $reply;
        });

        return response()->json(['replies' => $replies]);
    }

    // 該当するコメントを削除する
    public function deleteComment($comment_id)
    {
        // 編集の対象となるデータを取得
        $targetComment = WorkReviewComment::find($comment_id);
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
