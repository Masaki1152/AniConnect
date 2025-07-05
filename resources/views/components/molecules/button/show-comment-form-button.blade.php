@if ($comment)
    <button id='toggleChildComments-{{ $comment->id }}' type='button'
        class="px-2 py-1 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600"
        onclick="toggleChildCommentForm({{ $comment->id }})">{{ __('common.comment') }}</button>
    <button id='closeChildComments-{{ $comment->id }}' type='button'
        class="px-2 py-1 bg-gray-300 text-gray-700 rounded-lg shadow-md hover:bg-gray-400 hidden"
        onclick="toggleChildCommentForm({{ $comment->id }})">{{ __('common.close') }}</button>
@else
    <div class='content_fotter_comment'>
        <button id='toggleComments' type='button'
            class="px-2 py-1 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600"
            onclick="toggleCommentForm()">{{ __('common.comment') }}</button>
        <button id='closeComments' type='button'
            class="px-2 py-1 bg-gray-300 text-gray-700 rounded-lg shadow-md hover:bg-gray-400 hidden"
            onclick="toggleCommentForm()">{{ __('common.close') }}</button>
    </div>
@endif
