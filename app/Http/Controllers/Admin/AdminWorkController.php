<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\WorkRequest;
use App\Models\Work;
use Illuminate\Database\Eloquent\SoftDeletes;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class AdminWorkController extends Controller
{
    use SoftDeletes;

    public function index(Request $request, Work $work)
    {
        // 検索キーワードがあれば取得
        $search = $request->input('search', '');
        // キーワードに部分一致する投稿を取得
        $works = $work->fetchWorks($search);
        // 検索結果の件数を取得
        $totalResults = $works->total();

        return view('admin.works.index')->with([
            'works' => $works,
            'totalResults' => $totalResults,
            'search' => $search
        ]);
    }

    public function show($work_id)
    {
        $work = Work::find($work_id);
        return view('admin.works.show')->with(['work' => $work]);
    }

    // 新規投稿作成画面を表示する
    public function create()
    {
        return view('admin.works.create');
    }

    // 新しく記述した内容を保存する
    public function store(Work $work, WorkRequest $request)
    {
        $input_work = $request['works'];
        //cloudinaryへ画像を送信し、画像のURLを$image_urlに代入
        //画像ファイルが送られた時だけ処理が実行される
        if ($request->file('image')) {
            $image_url = Cloudinary::upload($request->file('image')->getRealPath(), [
                'transformation' => [
                    'width' => 600,
                    'height' => 800,
                    'crop' => 'pad',
                    'background' => 'white',
                ]
            ])->getSecurePath();
            $input_work['image'] = $image_url;
        }
        $work->fill($input_work)->save();
        $message = __('messages.new_work_registered');
        return redirect()->route('admin.works.show', ['work_id' => $work->id])->with('message', $message);
    }
}
