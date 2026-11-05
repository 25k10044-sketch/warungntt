@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block min-h-11 w-full border-l-4 border-[#7A1F1F] bg-[#FAF3E3] py-2 pe-4 ps-3 text-start text-base font-semibold text-[#7A1F1F] transition'
            : 'block min-h-11 w-full border-l-4 border-transparent py-2 pe-4 ps-3 text-start text-base font-medium text-stone-600 transition hover:border-[#D9A441] hover:bg-[#FAF3E3] hover:text-[#1F1A17]';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
