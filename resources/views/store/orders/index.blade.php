<x-app-layout>
    <section class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
        <p class="text-sm font-bold uppercase tracking-widest text-[#7A1F1F]">Akun Anda</p>
        <h1 class="mt-2 font-serif text-4xl font-bold text-[#1F1A17]">Pesanan saya</h1>
        @if ($orders->isEmpty())
            <div class="mt-8 rounded-2xl border border-dashed border-[#cbbd9e] bg-white p-10 text-center">
                <h2 class="font-serif text-2xl font-bold text-[#1F1A17]">Belum ada pesanan</h2>
                <p class="mt-2 text-stone-600">Pesananmu akan muncul di sini setelah checkout.</p>
                <a href="{{ route('menus.index') }}" class="mt-5 inline-flex min-h-11 items-center rounded-lg bg-[#7A1F1F] px-5 font-semibold text-white">Pilih menu</a>
            </div>
        @else
            <div class="mt-7 space-y-4">
                @foreach ($orders as $order)
                    @php
                        $statusClasses = [
                            'menunggu' => 'bg-amber-100 text-amber-900',
                            'diproses' => 'bg-blue-100 text-blue-900',
                            'siap' => 'bg-emerald-100 text-emerald-900',
                            'selesai' => 'bg-green-100 text-green-900',
                            'dibatalkan' => 'bg-red-100 text-red-900',
                        ];
                    @endphp
                    <a href="{{ route('orders.show', $order) }}" class="grid gap-4 rounded-2xl border border-[#eadfca] bg-white p-5 transition hover:border-[#D9A441] sm:grid-cols-[1fr_auto] sm:items-center">
                        <div>
                            <p class="font-bold text-[#1F1A17]">{{ $order->order_code }}</p>
                            <p class="mt-1 text-sm text-stone-500">{{ $order->created_at->format('d M Y, H:i') }} · {{ $order->order_items_count }} item</p>
                            <p class="mt-2 font-bold text-[#7A1F1F]">Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>
                        </div>
                        <span class="inline-flex min-h-9 items-center rounded-full px-4 text-sm font-semibold {{ $statusClasses[$order->status] ?? 'bg-stone-100 text-stone-800' }}">{{ ucfirst($order->status) }}</span>
                    </a>
                @endforeach
            </div>
            <div class="mt-8">{{ $orders->links() }}</div>
        @endif
    </section>
</x-app-layout>
