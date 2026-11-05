<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex min-h-11 items-center justify-center rounded-lg border border-[#cbbd9e] bg-white px-5 font-semibold text-[#1F1A17] shadow-sm transition-colors hover:bg-[#FAF3E3] disabled:opacity-50']) }}>
    {{ $slot }}
</button>
