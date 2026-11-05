<x-admin-layout title="Ringkasan">
    <div class="tenun-pattern mb-6 h-2 rounded-full" aria-hidden="true"></div>
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ([
            ['Total pesanan', number_format($totalOrders), 'Semua status'],
            ['Omzet selesai', 'Rp'.number_format($revenue, 0, ',', '.'), 'Pesanan berstatus selesai'],
            ['Menu aktif', number_format($menuCount), 'Semua menu'],
            ['Pelanggan', number_format($customerCount), 'Akun pelanggan'],
        ] as [$label, $value, $hint])
            <article class="rounded-2xl border border-[#eadfca] bg-white p-5 shadow-sm">
                <p class="text-sm font-semibold text-stone-500">{{ $label }}</p>
                <p class="mt-3 font-serif text-2xl font-bold text-[#7A1F1F]">{{ $value }}</p>
                <p class="mt-1 text-xs text-stone-500">{{ $hint }}</p>
            </article>
        @endforeach
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-[1.4fr_1fr]">
        <section class="rounded-2xl border border-[#eadfca] bg-white p-5">
            <div class="flex flex-wrap items-end justify-between gap-3">
                <div>
                    <p class="text-sm font-bold uppercase tracking-widest text-[#7A1F1F]">7 hari terakhir</p>
                    <h2 class="mt-1 font-serif text-2xl font-bold">Omzet harian</h2>
                </div>
                <a href="{{ route('admin.reports.index') }}" class="text-sm font-semibold text-[#7A1F1F] underline underline-offset-4">Lihat laporan</a>
            </div>
            <div class="mt-5 h-72"><canvas id="admin-revenue-chart" aria-label="Grafik omzet harian 7 hari terakhir" role="img"></canvas></div>
        </section>

        <section class="rounded-2xl border border-[#eadfca] bg-white p-5">
            <h2 class="font-serif text-2xl font-bold">Menu terlaris</h2>
            @if ($bestSellers->isEmpty())
                <p class="mt-5 text-sm text-stone-600">Belum ada data pesanan untuk ditampilkan.</p>
            @else
                <ol class="mt-4 divide-y divide-[#eadfca]">
                    @foreach ($bestSellers as $index => $item)
                        <li class="flex items-center justify-between gap-4 py-3">
                            <span class="font-medium">{{ $index + 1 }}. {{ $item->menu?->name ?? 'Menu dihapus' }}</span>
                            <span class="shrink-0 text-sm font-semibold text-[#7A1F1F]">{{ $item->quantity_sold }} porsi</span>
                        </li>
                    @endforeach
                </ol>
            @endif
        </section>
    </div>

    <section class="mt-6 rounded-2xl border border-[#eadfca] bg-white p-5">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h2 class="font-serif text-2xl font-bold">Pesanan terbaru</h2>
            <a href="{{ route('admin.orders.index') }}" class="text-sm font-semibold text-[#7A1F1F] underline underline-offset-4">Kelola semua pesanan</a>
        </div>
        @if ($latestOrders->isEmpty())
            <p class="mt-5 text-sm text-stone-600">Belum ada pesanan masuk.</p>
        @else
            <div class="mt-4 overflow-x-auto">
                <table class="w-full min-w-[42rem] text-left text-sm">
                    <thead class="border-b border-[#eadfca] text-stone-500"><tr><th class="py-3 pr-4">Kode</th><th class="py-3 pr-4">Pelanggan</th><th class="py-3 pr-4">Tanggal</th><th class="py-3 pr-4">Status</th><th class="py-3 text-right">Total</th></tr></thead>
                    <tbody class="divide-y divide-[#f1eadc]">
                        @foreach ($latestOrders as $order)
                            <tr>
                                <td class="py-3 pr-4"><a class="font-semibold text-[#7A1F1F] hover:underline" href="{{ route('admin.orders.show', $order) }}">{{ $order->order_code }}</a></td>
                                <td class="py-3 pr-4">{{ $order->user->name }}</td>
                                <td class="py-3 pr-4">{{ $order->created_at->format('d M Y, H:i') }}</td>
                                <td class="py-3 pr-4">{{ ucfirst($order->status) }}</td>
                                <td class="py-3 text-right font-semibold">Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
</x-admin-layout>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const canvas = document.getElementById('admin-revenue-chart');
            if (!canvas || !window.Chart) return;

            new window.Chart(canvas, {
                type: 'bar',
                data: {
                    labels: @js($chartLabels),
                    datasets: [{
                        label: 'Omzet selesai (Rp)',
                        data: @js($chartValues),
                        backgroundColor: '#7A1F1F',
                        borderRadius: 6,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, ticks: { callback: value => 'Rp' + Number(value).toLocaleString('id-ID') } } },
                },
            });
        });
    </script>
@endpush
