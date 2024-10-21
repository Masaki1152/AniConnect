<?php

namespace App\Http\Controllers;

use App\Models\WorkReview;
use App\Http\Requests\WorkReviewRequest;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class WorkReviewController extends Controller
{
    use SoftDeletes;

    // インポートしたPostをインスタンス化して$postとして使用。
    public function index(WorkReview $work_reviews, $work_id)
    {
        // blade内の変数postsにインスタンス化した$work_reviewsを代入
        // 指定したidのアニメの投稿のみを表示
        return view('work_reviews.index')->with(['posts' => $work_reviews->getPaginateByLimit($work_id), 'work' => $work_reviews->getRestrictedPost('work_id', $work_id)]);
    }

    // 'post'はbladeファイルで使う変数。
    public function show(WorkReview $workreview, $work_id, $post_id)
    {
        return view('work_reviews.show')->with(['post' => $workreview->getDetailPost($work_id, $post_id)]);
    }

    // 新規投稿作成画面を表示する
    public function create(WorkReview $workreview, $work_id)
    {
        // ログインしているユーザー名の取得
        $user = Auth::user()->name;
        return view('work_reviews.create')->with(['workreview' => $workreview->getRestrictedPost('work_id', $work_id), 'user' => $user]);
    }

    // 新しく記述した内容を保存する
    public function store(WorkReview $workreview, WorkReviewRequest $request)
    {
        $input = $request['work_review'];
        $workreview->fill($input)->save();
        return redirect()->route('work_reviews.show', ['work_id' => $workreview->work_id, 'post_id' => $workreview->id]);
    }

    // 感想投稿編集画面を表示する
    public function edit(WorkReview $workreview, $work_id, $post_id)
    {
        return view('work_reviews.edit')->with(['post' => $workreview->getDetailPost($work_id, $post_id)]);
    }

    // 感想投稿の編集を実行する
    public function update(WorkReviewRequest $request, WorkReview $workreview, $work_id, $post_id)
    {
        $input_post = $request['work_review'];
        // 編集の対象となるデータを取得
        $targetworkreview = $workreview->getDetailPost($work_id, $post_id);
        $targetworkreview->fill($input_post)->save();
        return redirect()->route('work_reviews.show', ['work_id' => $targetworkreview->work_id, 'post_id' => $targetworkreview->id]);
    }

    // 感想投稿を削除する
    public function delete(WorkReview $workreview, $work_id, $post_id)
    {
        // 編集の対象となるデータを取得
        $targetworkreview = $workreview->getDetailPost($work_id, $post_id);
        $targetworkreview->delete();
        return redirect()->route('work_reviews.index', ['work_id' => $work_id]);
    }
}
