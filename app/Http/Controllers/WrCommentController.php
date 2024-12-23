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

        // Bladeテンプレートをレンダリング
        $commentHtml = view('comments.input_comment', ['comment' => $wr_comment, 'status' => 'stored'])->render();
        return response()->json(['message' => 'コメントを投稿しました。', 'commentHtml' => $commentHtml]);
    }

    // コメントを削除する
    public function delete($comment_id)
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

        return back()->with('status', '投稿を削除しました');
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
            $reply->is_liked_by_user = $reply->users->contains(auth()->user());
            $reply->like_user_count = $reply->users->count();
            return $reply;
        });

        return response()->json($replies);
    }
}
