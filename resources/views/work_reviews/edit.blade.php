<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>Blog</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
</head>

<body>
    <h1 class="title">{{ $work_review->work->name }}への投稿編集画面</h1>
    <div class="content">
        <form action="{{ route('work_reviews.update', ['work_id' => $work_review->work_id, 'work_review_id' => $work_review->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="work_id">
                <input type="hidden" name="work_review[work_id]" value="{{ $work_review->work_id }}">
            </div>
            <div class="user_id">
                <input type="hidden" name="work_review[user_id]" value="{{ $work_review->user_id }}">
            </div>
            <div class="title">
                <h2>タイトル</h2>
                <input type="text" name="work_review[post_title]" placeholder="タイトル" value="{{ $work_review->post_title }}" />
                <p class="title__error" style="color:red">{{ $errors->first('work_review.post_title') }}</p>
            </div>
            <div class="category">
                <h2>カテゴリー（3個まで）</h2>
                @php
                // old() が存在すればそれを使用し、なければ過去の保存値を使用
                $existingCategories = $work_review->categories->pluck('id')->toArray();
                $selectedCategories = old('work_review.categories_array', $existingCategories);
                @endphp
                <select name="work_review[categories_array][]" multiple>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ in_array($category->id, $selectedCategories) ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>

                @if ($errors->has('work_review.categories_array'))
                <p class="category__error" style="color:red">{{ $errors->first('work_review.categories_array') }}</p>
                @endif
            </div>
            <div class="body">
                <h2>内容</h2>
                <textarea name="work_review[body]" placeholder="内容を記入してください。">{{ $work_review->body }}</textarea>
                <p class="body__error" style="color:red">{{ $errors->first('work_review.body') }}</p>
            </div>
            <div class="image">
                <h2>画像（4枚まで）</h2>
                <input type="file" name="images[]" multiple>
                <p class="image__error" style="color:red">{{ $errors->first('images') }}</p>
            </div>
            <button type="submit">変更を保存する</button>
        </form>
    </div>
    <div class="footer">
        <a href="{{ route('work_reviews.show', ['work_id' => $work_review->work_id, 'work_review_id' => $work_review->id]) }}">保存しないで戻る</a>
    </div>
</body>