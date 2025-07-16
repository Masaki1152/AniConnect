<!-- TODO: ログインダイアログのデザインは今後改修予定 -->
<div id="login-dialog" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl p-6 m-4 max-w-sm w-full relative">
        <button id="close-login-dialog"
            class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>
        <h3 class="text-lg font-semibold text-center mb-4">ログインが必要です</h3>
        <p class="text-gray-700 text-center mb-6"></p>
        <div class="flex flex-col items-center gap-6">
            <a href="{{ route('login') }}"
                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                ログインする
            </a>
            <a href="{{ route('register') }}"
                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                会員登録する
            </a>
        </div>
    </div>
</div>
