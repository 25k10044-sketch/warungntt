@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'ntt-form-control']) }}>
