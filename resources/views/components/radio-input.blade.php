@props(['disabled' => false, 'name' => '', 'value' => '', 'checked' => false, 'label' => ''])

<label class="inline-flex items-center space-x-2">
    <input type="radio" name="{{ $name }}" value="{{ $value }}" {{ $disabled ? 'disabled' : '' }}
        {{ old($name) === $value || $checked ? 'checked' : '' }} {!! $attributes->merge([
            'class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm',
        ]) !!}>
    <span class="text-gray-700">{{ $label }}</span>
</label>
