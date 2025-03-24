<div class="content mt-2">
    <p class="flex items-center text-lg font-semibold text-gray-800">
        <span class="mr-2 mt-1">{{ __('common.evaluation') }}</span>
        @for ($i = 1; $i <= 5; $i++)
            <svg class="inline-block w-6 h-6 {{ $i <= $starNum ? 'text-yellow-400' : 'text-gray-300' }}"
                fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M12 .587l3.668 7.431 8.208 1.192-5.938 5.792 1.398 8.168L12 18.891l-7.336 3.856 1.398-8.168-5.938-5.792 8.208-1.192L12 .587z" />
            </svg>
        @endfor
    </p>
</div>
