<x-admin-layout title="Laporan Penjualan">
    <form method="GET" class="grid gap-3 rounded-2xl border border-[#eadfca] bg-white p-4 sm:grid-cols-2 lg:grid-cols-[12rem_1fr_1fr_auto] lg:items-end">
        <div>
            <label for="period" class="text-sm font-semibold">Grafik</label>
            <select id="period" name="period" class="mt-1 min-h-11 w-full rounded-lg border-stone-300 focus:border-[#7A1F1F] focus:ring-[#7A1F1F]">
                <option value="harian" @selected($period === 'harian')>Harian</option>
                <option value="bulanan" @selected($period === 'bulanan')>Bulanan</option>
            </select>
        </div>
        <div>
            <label for="from" class="text-sm font-semibold">Dari tanggal</label>
            <input id="from" type="date" name="from" value="{{ request('from', $from->toDateString()) }}" class="mt-1 min-h-11 w-full rounded-lg border-stone-300 focus:border-[#7A1F1F] focus:ring-[#7A1F1F]">
        </div>
        <div>
            <label for="to" class="text-sm font-semibold">Sampai tanggal</label>
            <input id="to" type="date" name="to" value="{{ request('to', $to->toDateString()) }}" class="mt-1 min-h-11 w-full rounded-lg border-stone-300 focus:border-[#7A1F1F] focus:ring-[#7A1F1F]">
        </div>
        <button class="min-h-11 rounded-lg bg-[#7A1F1F] px-5 font-semibold text-white">Tampilkan</button>
    </form>

    <div class="mt-5 grid gap-4 sm:grid-cols-2">
        <article class="rounded-2xl border border-[#eadfca] bg-white p-5">
            <p class="text-sm font-semibold text-stone-500">Omzet pesanan selesai</p>
            <p class="mt-2 font-serif text-2xl font-bold text-[#7A1F1F]">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </article>
        <article class="rounded-2xl border border-[#eadfca] bg-white p-5">
            <p class="text-sm font-semibold text-stone-500">Pesanan selesai</p>
            <p class="mt-2 font-serif text-2xl font-bold">{{ number_format($completedOrders) }}</p>
        </article>
    </div>

    <section class="mt-5 rounded-2xl border border-[#eadfca] bg-white p-5">
        <h2 class="font-serif text-xl font-bold">Grafik omzet {{ $period }}</h2>
        <div class="mt-4 h-80"><canvas id="sales-chart" aria-label="Grafik omzet penjualan {{ $period }}" role="img"></canvas></div>
        @if ($totalRevenue === 0)<p class="mt-3 text-sm text-stone-500">Belum ada penjualan selesai pada rentang tanggal ini.</p>@endif
    </section>

    <section class="mt-5 rounded-2xl border border-[#eadfca] bg-white p-5">
        <h2 class="font-serif text-xl font-bold">Menu terlaris pada periode ini</h2>
        @if ($bestSellers->isEmpty())
            <p class="mt-4 text-sm text-stone-600">Belum ada menu terjual pada rentang tanggal ini.</p>
        @else
            <div class="mt-4 overflow-x-auto">
                <table class="w-full min-w-[32rem] text-left text-sm">
                    <thead class="border-b border-[#eadfca] text-stone-500"><tr><th class="py-3 pr-4">Menu</th><th class="py-3 pr-4">Jumlah terjual</th><th class="py-3 text-right">Omzet item</th></tr></thead>
                    <tbody class="divide-y divide-[#f1eadc]">
                        @foreach ($bestSellers as $item)
                            <tr><td class="py-3 pr-4 font-semibold">{{ $item->menu?->name ?? 'Menu dihapus' }}</td><td class="py-3 pr-4">{{ $item->quantity_sold }} porsi</td><td class="py-3 text-right">Rp{{ number_format($item->revenue, 0, ',', '.') }}</td></tr>
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
            const canvas = document.getElementById('sales-chart');
            if (!canvas || !window.Chart) return;

            new window.Chart(canvas, {
                type: 'line',
                data: {
                    labels: @js($labels),
                    datasets: [{
                        label: 'Omzet selesai (Rp)',
                        data: @js($values),
                        borderColor: '#7A1F1F',
                        backgroundColor: 'rgba(217, 164, 65, .25)',
                        fill: true,
                        tension: 0.25,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: true } },
                    scales: { y: { beginAtZero: true, ticks: { callback: value => 'Rp' + Number(value).toLocaleString('id-ID') } } },
                },
            });
        });
    </script>
@endpush
