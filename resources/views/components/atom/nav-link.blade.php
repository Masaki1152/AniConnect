@props(['active'])

@php
    $linkClasses =
        'inline-flex items-center px-1 pt-4 pb-4 border-b-2 focus:outline-none transition duration-150 ease-in-out';
    $spanClasses = 'font-semibold leading-5';

    if ($active) {
        $linkClasses .= ' border-b-4 border-activeBorderBlue';
        $spanClasses .= ' text-activeTextDark';
    } else {
        $linkClasses .= ' border-transparent hover:border-activeBorderBlue';
        $spanClasses .= ' text-textColor hover:text-textColorHover';
    }
@endphp

<a {{ $attributes->merge(['class' => $linkClasses]) }}>
    <span class="{{ $spanClasses }}">
        {{ $slot }}
    </span>
</a>
