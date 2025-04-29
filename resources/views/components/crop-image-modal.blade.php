<!-- 画像トリミング用のモーダルウィンドウ表示 -->
<div id="crop-modal" class="crop-modal hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-4 rounded-lg shadow-lg relative w-full max-w-2xl">
        <div class="overflow-hidden w-full h-auto">
            <img id="crop-preview" class="crop-preview w-full h-auto object-cover" />
        </div>
        <div class="flex justify-end gap-2 mt-4">
            <button id="crop-cancel-button" type="button"
                class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                {{ __('common.cancel') }}
            </button>
            <button id="crop-next-button" type="button"
                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                {{ __('common.next') }}
            </button>
        </div>
    </div>
</div>
