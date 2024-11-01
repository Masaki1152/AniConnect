<!DOCTYPE HTML>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>作品の感想投稿</title>
</head>

<body>
    <h1>「{{$workreview->work->name}}」への新規感想投稿</h1>
    <form action="{{ route('work_reviews.store', ['work_id' => $workreview->work_id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="work_id">
            <input type="hidden" name="work_review[work_id]" value="{{ $workreview->work_id }}">
        </div>
        <div class="title">
            <h2>タイトル</h2>
            <input type="text" name="work_review[post_title]" placeholder="タイトル" value="{{ old('work_review.post_title') }}" />
            <p class="title__error" style="color:red">{{ $errors->first('work_review.post_title') }}</p>
        </div>
        <div class="category">
            <h2>カテゴリー（3個まで）</h2>
            <select name="work_review[categories_array][]" multiple>
                @foreach($categories as $category)
                <option value="{{ $category->id }}" @if(in_array($category->id, old('work_review.categories_array', []))) selected @endif>
                    {{$category->name}}
                </option>
                @endforeach
            </select>
            @if ($errors->has('work_review.categories_array'))
            <p class="category__error" style="color:red">{{ $errors->first('work_review.categories_array') }}</p>
            @endif
        </div>
        <div class="body">
            <h2>内容</h2>
            <textarea name="work_review[body]" placeholder="内容を記入してください。">{{ old('work_review.body') }}</textarea>
            <p class="body__error" style="color:red">{{ $errors->first('work_review.body') }}</p>
        </div>
        <div class="image">
            <h2>画像（4枚まで）</h2>
            <input type="file" name="images[]" multiple>
            <p class="image__error" style="color:red">{{ $errors->first('images') }}</p>
        </div>
        <button type="submit">投稿する</button>
    </form>
    <div class="footer">
        <a href="{{ route('work_reviews.index', ['work_id' => $workreview->work_id]) }}">戻る</a>
    </div>
</body>

</html>