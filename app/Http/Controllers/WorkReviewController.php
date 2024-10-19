<?php

namespace App\Http\Controllers;

use App\Models\WorkReview;
use App\Http\Requests\WorkReviewRequest;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkReviewController extends Controller
{
    use SoftDeletes;

    // インポートしたPostをインスタンス化して$postとして使用。
    public function index(WorkReview $work_reviews)
    {
        // blade内の変数postsにインスタンス化した$work_reviewsを代入
        return view('work_reviews.index')->with(['posts' => $work_reviews->getPaginateByLimit()]);
    }

    // 'post'はbladeファイルで使う変数。$postはid=1のWorkReviewインスタンス。
    public function show(WorkReview $workreview)
    {
        return view('work_reviews.show')->with(['post' => $workreview]);
    }

    // 新規投稿作成画面を表示する
    public function create()
    {
        return view('work_reviews.create');
    }

    // 新しく記述した内容を保存する
    public function store(WorkReview $workreview, WorkReviewRequest $request)
    {
        $input = $request['work_review'];
        $workreview->fill($input)->save();
        return redirect('/work_reviews/' . $workreview->id);
    }

    // 感想投稿編集画面を表示する
    public function edit(WorkReview $workreview)
    {
        return view('work_reviews.edit')->with(['post' => $workreview]);
    }

    // 感想投稿の編集を実行する
    public function update(WorkReviewRequest $request, WorkReview $workreview)
    {
        $input_post = $request['work_review'];
        $workreview->fill($input_post)->save();
        return redirect('/work_reviews/' . $workreview->id);
    }

    // 感想投稿を削除する
    public function delete(WorkReview $workreview)
    {
        $workreview->delete();
        return redirect('/');
    }
}
