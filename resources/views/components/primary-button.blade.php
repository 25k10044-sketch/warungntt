<button {{ $attributes->merge(['type' => 'submit', 'class' => 'ntt-primary-button']) }}>
    {{ $slot }}
</button>
