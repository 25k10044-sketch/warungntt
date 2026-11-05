@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex min-h-11 items-center border-b-2 border-[#7A1F1F] px-3 text-sm font-semibold text-[#7A1F1F] transition'
            : 'inline-flex min-h-11 items-center border-b-2 border-transparent px-3 text-sm font-semibold text-stone-600 transition hover:border-[#D9A441] hover:text-[#7A1F1F]';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
