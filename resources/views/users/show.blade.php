<x-app-layout>
    @if (session('status'))
        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
            class="fixed top-[15%] left-1/2 transform -translate-x-1/2 bg-green-500/50 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-4 z-50">
            <div class="text-white">
                {{ session('status') }}
            </div>
        </div>
    @endif

    <div class="px-4 py-6 flex flex-row gap-6">
        <div class="basis-2/3">
            <!-- プロフィールブロック -->
            <div class="flex flex-col gap-4">
                <div class="title text-xl font-semibold ml-4">
                    プロフィール
                </div>
                <!-- プロフィール詳細ブロック -->
                <div class="bg-white rounded-lg shadow-md">
                    <div class="w-full p-4 border rounded-lg bg-gray-50">
                        @include('users.input_profile', ['user' => $user])
                    </div>
                </div>
                <div class="bg-sky-100">曜ちゃん！</div>
                <div class="bg-sky-200">テキストが入ります</div>
                <div class="bg-sky-300">テキストが入ります</div>
                <div class="bg-sky-400">テキストが入ります</div>
                <div class="bg-sky-500">テキストが入ります</div>
            </div>
        </div>
        <div class="bg-sky-400 basis-1/3">凛ちゃん</div>
    </div>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('/js/follow_user.js') }}"></script>
</x-app-layout>
