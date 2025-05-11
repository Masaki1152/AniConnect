<?php

namespace App\Http\Controllers\Admin\RelatedParty;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\RelatedParty\CreatorRequest;
use App\Models\Creator;
use Illuminate\Database\Eloquent\SoftDeletes;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Traits\CommonFunction;

class AdminCreatorController extends Controller
{
    use SoftDeletes;
    use CommonFunction;

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
        //cloudinaryへ画像を送信し、画像のURLを$image_urlに代入
        //画像ファイルが送られた時だけ処理が実行される
        if ($request->file('image')) {
            $image_url = Cloudinary::upload($request->file('image')->getRealPath(), [
                'transformation' => [
                    'width' => 800,
                    'height' => 600,
                    'crop' => 'pad',
                    'background' => 'white',
                ]
            ])->getSecurePath();
            $input_creator['image'] = $image_url;
        }
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
        // 画像の編集
        // 元々ファイルがあり、さらにそのファイルを変更する場合
        if ($creator->image && $request->hasFile('image')) {
            // 既存のファイルパスの削除
            $currentImage = $creator->image;
            $public_id = $this->extractPublicIdFromUrl($currentImage);
            Cloudinary::destroy($public_id);
            // 新しいファイルの追加
            $path = Cloudinary::upload($request['image']->getRealPath())->getSecurePath();
            $creator->image = $path;
        } elseif ($request->hasFile('image')) {
            // 元々ファイルがなく、ファイルの変更がある場合
            // 新しいファイルの追加
            $path = Cloudinary::upload($request['image']->getRealPath())->getSecurePath();
            $creator->image = $path;
        } elseif ($creator->image && $request['existingImage'] == null) {
            // 元々ファイルがあり、画像が削除された場合
            // 既存のファイルパスの削除
            $currentImage = $creator->image;
            $public_id = $this->extractPublicIdFromUrl($currentImage);
            Cloudinary::destroy($public_id);
            $creator->image = null;
            // 元々ファイルがないor元々ファイルがある場合で、ファイルの変更がない場合は何もしない
        }

        $creator->fill($input_creator)->save();
        $message = __('messages.new_creator_updated');
        return redirect()->route('admin.creators.show', ['creator_id' => $creator->id])->with('message', $message);
    }

    // 制作会社を削除する
    public function delete($creator_id)
    {
        // 削除の対象となるデータを取得
        $creator = Creator::find($creator_id);
        // 削除する投稿の画像も削除する処理
        $removed_image_path = $creator->image;
        // DBのimageの中身がnullでなければ処理を行う
        if ($removed_image_path !== null) {
            $public_id = $this->extractPublicIdFromUrl($removed_image_path);
            Cloudinary::destroy($public_id);
        }
        // データの削除
        $creator->delete();
        $message = __('messages.new_creator_deleted');
        return redirect()->route('admin.creators.index', ['creator_id' => $creator->id])->with('message', $message);
    }
}
