@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-semibold text-[#1F1A17]']) }}>
    {{ $value ?? $slot }}
</label>
