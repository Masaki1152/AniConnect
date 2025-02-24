<div class="interested inline-flex items-center gap-2 border border-gray-300 p-1.5 rounded-lg shadow-sm">
    <div class="text-gray-700 font-semibold">
        気になる
    </div>
    <button id="interested_button" data-type="{{ $type }}" data-id="{{ $root->id }}" type="submit"
        class="px-1 text-lg hover:bg-gray-200 transition 
        {{ $root->users->contains(auth()->user()) ? 'text-yellow-400' : 'text-gray-400 hover:text-gray-600' }}">
        {{ $root->users->contains(auth()->user()) ? '★' : '☆' }}
    </button>
    <div class="interested_user text-sm text-gray-600">
        <a href="{{ route($path, $prop) }}" class="hover:underline">
            <p id="interested_count">{{ $root->users->count() }}件</p>
        </a>
    </div>
</div>
