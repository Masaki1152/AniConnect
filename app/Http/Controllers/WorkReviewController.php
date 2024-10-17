<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkReview;

class WorkReviewController extends Controller
{
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
}
