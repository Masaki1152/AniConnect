<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work_Reviews;

class Work_Review_PostController extends Controller
{
    //インポートしたPostをインスタンス化して$postとして使用。
    public function index(Work_Reviews $work_reviews)
    {
        return $work_reviews->get();
    }
}
