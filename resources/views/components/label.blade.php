@props(['value'])

<label {{ $attributes->merge(['class' => 'pr-1 fa-sm text-white-50 block font-medium text-sm text-gray-700']) }}>
    {{ $value ?? $slot }}
</label>
