<x-admin-layout title="Kelola Pesanan">
    <form method="GET" class="grid gap-3 rounded-2xl border border-[#eadfca] bg-white p-4 sm:grid-cols-[1fr_14rem_auto]">
        <label class="sr-only" for="order-search">Cari pesanan atau pelanggan</label>
        <input id="order-search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Cari kode pesanan, nama, atau email..." class="min-h-11 rounded-lg border-stone-300 focus:border-[#7A1F1F] focus:ring-[#7A1F1F]">
        <label class="sr-only" for="order-status">Filter status</label>
        <select id="order-status" name="status" class="min-h-11 rounded-lg border-stone-300 focus:border-[#7A1F1F] focus:ring-[#7A1F1F]">
            <option value="">Semua status</option>
            @foreach (['menunggu', 'diproses', 'siap', 'selesai', 'dibatalkan'] as $status)
                <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
        <button class="min-h-11 rounded-lg bg-[#7A1F1F] px-5 font-semibold text-white">Filter</button>
    </form>

    @if ($orders->isEmpty())
        <div class="mt-6 rounded-2xl border border-dashed border-[#cbbd9e] bg-white p-10 text-center text-stone-600">Belum ada pesanan sesuai filter.</div>
    @else
        <div class="mt-6 overflow-hidden rounded-2xl border border-[#eadfca] bg-white">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[58rem] text-left text-sm">
                    <thead class="bg-[#f3ead8]"><tr><th class="px-4 py-3">Kode pesanan</th><th class="px-4 py-3">Pelanggan</th><th class="px-4 py-3">Tanggal</th><th class="px-4 py-3">Item</th><th class="px-4 py-3">Status</th><th class="px-4 py-3 text-right">Total</th></tr></thead>
                    <tbody class="divide-y divide-[#f1eadc]">
                        @foreach ($orders as $order)
                            @php
                                $statusClasses = ['menunggu' => 'bg-amber-100 text-amber-900', 'diproses' => 'bg-blue-100 text-blue-900', 'siap' => 'bg-emerald-100 text-emerald-900', 'selesai' => 'bg-green-100 text-green-900', 'dibatalkan' => 'bg-red-100 text-red-900'];
                            @endphp
                            <tr>
                                <td class="px-4 py-3"><a href="{{ route('admin.orders.show', $order) }}" class="font-semibold text-[#7A1F1F] hover:underline">{{ $order->order_code }}</a></td>
                                <td class="px-4 py-3"><span class="block font-medium">{{ $order->user->name }}</span><span class="text-xs text-stone-500">{{ $order->user->email }}</span></td>
                                <td class="px-4 py-3">{{ $order->created_at->format('d M Y, H:i') }}</td>
                                <td class="px-4 py-3">{{ $order->order_items_count }}</td>
                                <td class="px-4 py-3"><span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses[$order->status] ?? 'bg-stone-100 text-stone-800' }}">{{ ucfirst($order->status) }}</span></td>
                                <td class="px-4 py-3 text-right font-semibold">Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="border-t border-[#eadfca] px-4 py-3">{{ $orders->links() }}</div>
        </div>
    @endif
</x-admin-layout>
