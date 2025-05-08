<?php

namespace App\Http\Controllers\Admin\RelatedParty;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\CreatorRequest;
use App\Models\Creator;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminCreatorController extends Controller
{
    use SoftDeletes;

    public function index(Request $request, Creator $creator)
    {
        // 検索キーワードがあれば取得
        $search = $request->input('search', '');
        // キーワードに部分一致する制作会社を取得
        $creators = $creator->fetchObjects($search, $creator);
        // 検索結果の件数を取得
        $totalResults = $creators->total();

        return view('admin.relatedParty.creators.index')->with([
            'creators' => $creators,
            'totalResults' => $totalResults,
            'search' => $search
        ]);
    }

    public function show($creator_id)
    {
        $creator = Creator::find($creator_id);
        return view('admin.relatedParty.creators.show')->with(['creator' => $creator]);
    }

    // 制作会社登録画面を表示する
    public function create()
    {
        return view('admin.relatedParty.creators.create');
    }

    // 新しく記述した内容を保存する
    public function store(Creator $creator, CreatorRequest $request)
    {
        $input_creator = $request['creators'];
        $creator->fill($input_creator)->save();
        $message = __('messages.new_creator_registered');
        return redirect()->route('admin.creators.show', ['creator_id' => $creator->id])->with('message', $message);
    }

    // 制作会社編集画面を表示する
    public function edit($creator_id)
    {
        $creator = Creator::find($creator_id);
        return view('admin.relatedParty.creators.edit')->with(['creator' => $creator]);
    }

    // 制作会社の編集を実行する
    public function update(CreatorRequest $request, $creator_id)
    {
        $input_creator = $request['creators'];
        // 編集の対象となるデータを取得
        $creator = Creator::find($creator_id);
        $creator->fill($input_creator)->save();
        $message = __('messages.new_creator_updated');
        return redirect()->route('admin.creators.show', ['creator_id' => $creator->id])->with('message', $message);
    }

    // 制作会社を削除する
    public function delete($creator_id)
    {
        // 削除の対象となるデータを取得
        $creator = Creator::find($creator_id);
        // データの削除
        $creator->delete();
        $message = __('messages.new_creator_deleted');
        return redirect()->route('admin.creators.index', ['creator_id' => $creator->id])->with('message', $message);
    }
}
