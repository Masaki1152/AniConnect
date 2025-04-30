<?php

namespace App\Http\Controllers\Work;

use App\Http\Controllers\Controller;
use App\Models\Work;
use App\Models\WorkReview;
use App\Models\WorkReviewCategory;
use Illuminate\Http\Request;
use App\Http\Requests\WorkReviewRequest;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class WorkReviewController extends Controller
{
    use SoftDeletes;

    // インポートしたWorkreviewをインスタンス化して$work_reviewsとして使用。
    public function index(Request $request, WorkReview $workReview, WorkReviewCategory $category, $work_id)
    {
        // クリックされたカテゴリーidを取得
        $categoryIds = $request->filled('checkedCategories')
            ? ($request->input('checkedCategories'))
            : [];
        // 検索キーワードがあれば取得
        $search = $request->input('search', '');
        // キーワードに部分一致する投稿を取得
        $work_reviews = $workReview->fetchWorkReviews($work_id, $search, $categoryIds);
        // 検索結果の件数を取得
        $totalResults = $work_reviews->total();

        // 単体の作品投稿オブジェクトを取得
        $work_review_first = WorkReview::where('work_id', $work_id)->first();
        // 作品のオブジェクトを取得
        $work = Work::find($work_id);

        // カテゴリー検索で選択されたカテゴリーをまとめる
        $selectedCategories = [];
        // カテゴリーの情報を取得する
        foreach ($categoryIds as $categoryId) {
            $category = WorkReviewCategory::find($categoryId);
            array_push($selectedCategories, $category->name);
        }

        return view('work_reviews.index')->with([
            'work_reviews' => $work_reviews,
            'work_review_first' => $work_review_first,
            'work' => $work,
            'work_id' => $work_id,
            'categories' => $category->get(),
            'totalResults' => $totalResults,
            'search' => $search,
            'selectedCategories' => $selectedCategories
        ]);
    }

    // 'work_review'はbladeファイルで使う変数。
    public function show(WorkReview $workreview, WorkReviewCategory $category, $work_id, $work_review_id)
    {
        return view('work_reviews.show')->with(['work_review' => $workreview->getDetailPost($work_id, $work_review_id), 'categories' => $category->get()]);
    }

    // 新規投稿作成画面を表示する
    public function create(WorkReview $workreview, WorkReviewCategory $category, $work_id)
    {
        // 作品のオブジェクトを取得
        $work = Work::find($work_id);
        return view('work_reviews.create')->with(['workreview' => $workreview->getRestrictedPost('work_id', $work_id), 'work' => $work, 'categories' => $category->get()]);
    }

    // 新しく記述した内容を保存する
    public function store(WorkReview $workreview, WorkReviewRequest $request)
    {
        $input_review = $request['work_review'];
        $input_categories = $request->work_review['categories_array'];
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
                $input_review += ["image$counter" => $image_url];
                $counter++;
            }
        }
        // ログインしているユーザーidの登録
        $input_review['user_id'] = Auth::id();
        $workreview->fill($input_review)->save();
        // カテゴリーとの中間テーブルにデータを保存
        $workreview->categories()->attach($input_categories);
        $message = __('messages.new_post_created');
        return redirect()->route('work_reviews.show', ['work_id' => $workreview->work_id, 'work_review_id' => $workreview->id])->with('message', $message);
    }

    // 感想投稿編集画面を表示する
    public function edit(WorkReview $workreview, WorkReviewCategory $category, $work_id, $work_review_id)
    {
        return view('work_reviews.edit')->with(['work_review' => $workreview->getDetailPost($work_id, $work_review_id), 'categories' => $category->get()]);
    }

    // 感想投稿の編集を実行する
    public function update(WorkReviewRequest $request, WorkReview $workreview, $work_id, $work_review_id)
    {
        $input_review = $request['work_review'];
        $input_categories = $request->work_review['categories_array'];

        // 保存する画像のPathの配列
        $image_paths = [];
        // 削除されていない既存画像がある場合のみ以下の処理を実行
        if ($request['remainedImages'][0]) {
            // JSON文字列をデコードしてPHP配列に変換
            $remained_images = json_decode($request['remainedImages'][0], true);
            // Pathの配列に削除されていない画像のPathを追加
            foreach ($remained_images as $remained_image) {
                array_push($image_paths, $remained_image['url']);
            }
        }
        // 削除された既存画像がある場合のみ以下の処理を実行
        if ($request['removedImages'][0]) {
            // JSON文字列をデコードしてPHP配列に変換
            $removed_images = json_decode($request['removedImages'][0], true);
            // 削除された画像のPathをCloudinaryから削除
            foreach ($removed_images as $removed_image) {
                // Cloudinaryに登録した画像のURLからpublic_idを取得する
                $public_id = $this->extractPublicIdFromUrl($removed_image['url']);
                Cloudinary::destroy($public_id);
            }
        }
        //cloudinaryへ画像を送信し、画像のURLを$image_urlに代入
        //画像ファイルが送られた時だけ処理が実行される
        if ($request->file('images')) {
            foreach ($request->file('images') as $image) {
                $image_path = Cloudinary::upload($image->getRealPath(), [
                    'transformation' => [
                        'width' => 800,
                        'height' => 600,
                        'crop' => 'pad',
                        'background' => 'white',
                    ]
                ])->getSecurePath();
                array_push($image_paths, $image_path);
            }
        }
        // $imagePathのうち、Pathのないものにはnullを代入
        $vacantElementNum = 4 - count($image_paths);
        for ($counter = 0; $counter < $vacantElementNum; $counter++) {
            array_push($image_paths, NULL);
        }
        $counter = 1;
        // 今回保存するPathをDBのImageカラムに代入する
        foreach ($image_paths as $imagePath) {
            $input_review["image$counter"] = $imagePath;
            $counter++;
        }
        // 編集の対象となるデータを取得
        $targetworkreview = $workreview->getDetailPost($work_id, $work_review_id);
        $targetworkreview->fill($input_review)->save();
        // カテゴリーとの中間テーブルにデータを保存
        // 中間テーブルへの紐づけと解除を行うsyncメソッドを使用
        $targetworkreview->categories()->sync($input_categories);
        $message = __('messages.post_edited');
        return redirect()->route('work_reviews.show', ['work_id' => $targetworkreview->work_id, 'work_review_id' => $targetworkreview->id])->with('message', $message);
    }

    // 感想投稿を削除する
    public function delete(WorkReview $workreview, $work_id, $work_review_id)
    {
        // 編集の対象となるデータを取得
        $targetworkreview = $workreview->getDetailPost($work_id, $work_review_id);
        // 削除する投稿の画像も削除する処理
        for ($counter = 1; $counter < 5; $counter++) {
            $removed_image_path = $targetworkreview->{'image' . $counter};
            // DBのimageの中身がnullであれば処理をスキップする
            if (is_null($removed_image_path)) {
                break;
            }
            $public_id = $this->extractPublicIdFromUrl($removed_image_path);
            Cloudinary::destroy($public_id);
        }

        // 投稿へのコメントの画像も削除する処理
        $comments = $targetworkreview->workReviewComments;
        foreach ($comments as $comment) {
            for ($counter = 1; $counter < 5; $counter++) {
                $comment_image_path = $comment->{'image' . $counter};
                if (is_null($comment_image_path)) {
                    continue;
                }
                $public_id = $this->extractPublicIdFromUrl($comment_image_path);
                Cloudinary::destroy($public_id);
            }
            // コメント自体も削除
            $comment->delete();
        }

        // データの削除
        $targetworkreview->delete();
        $message = __('messages.post_deleted');
        return redirect()->route('work_reviews.index', ['work_id' => $work_id])->with('message', $message);
    }

    // 投稿にいいねを行う
    public function like($work_id, $work_review_id)
    {
        // 投稿が見つからない場合の処理
        $work_review = WorkReview::find($work_review_id);
        if (!$work_review) {
            return response()->json(['message' => 'Post not found'], 404);
        }
        // 現在ログインしているユーザーが既にいいねしていればtrueを返す
        $isLiked = $work_review->users()->where('user_id', Auth::id())->exists();
        if ($isLiked) {
            // 既にいいねしている場合
            $work_review->users()->detach(Auth::id());
            $status = 'unliked';
            $message = __('messages.unliked');
        } else {
            // 初めてのいいねの場合
            $work_review->users()->attach(Auth::id());
            $status = 'liked';
            $message = __('messages.liked');
        }
        // いいねしたユーザー数の取得
        $count = count($work_review->users()->pluck('work_review_id')->toArray());

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
}
