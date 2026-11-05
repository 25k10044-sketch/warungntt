<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex min-h-11 items-center justify-center rounded-lg border border-transparent bg-red-700 px-5 font-semibold text-white transition-colors hover:bg-red-800']) }}>
    {{ $slot }}
</button>
