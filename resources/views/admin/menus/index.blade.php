<x-admin-layout title="Kelola Menu">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <form method="GET" class="flex min-w-0 flex-1 gap-2 sm:max-w-xl">
            <label class="sr-only" for="menu-search">Cari menu</label>
            <input id="menu-search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Cari nama atau slug menu..." class="min-h-11 min-w-0 flex-1 rounded-lg border-stone-300 focus:border-[#7A1F1F] focus:ring-[#7A1F1F]">
            <button class="min-h-11 rounded-lg border border-[#7A1F1F] px-4 font-semibold text-[#7A1F1F]">Cari</button>
        </form>
        <a href="{{ route('admin.menus.create') }}" class="inline-flex min-h-11 items-center rounded-lg bg-[#7A1F1F] px-5 font-semibold text-white hover:bg-[#5f1717]">+ Tambah Menu</a>
    </div>

    @if ($menus->isEmpty())
        <div class="mt-6 rounded-2xl border border-dashed border-[#cbbd9e] bg-white p-10 text-center text-stone-600">Belum ada menu yang cocok.</div>
    @else
        <div class="mt-6 overflow-hidden rounded-2xl border border-[#eadfca] bg-white">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[58rem] text-left text-sm">
                    <thead class="bg-[#f3ead8] text-[#1F1A17]"><tr><th class="px-4 py-3">Menu</th><th class="px-4 py-3">Kategori</th><th class="px-4 py-3">Harga</th><th class="px-4 py-3">Status</th><th class="px-4 py-3">Item pesanan</th><th class="px-4 py-3 text-right">Aksi</th></tr></thead>
                    <tbody class="divide-y divide-[#f1eadc]">
                        @foreach ($menus as $menu)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        @if ($menu->image)
                                            <img class="size-12 rounded-lg object-cover" src="{{ asset('storage/'.$menu->image) }}" alt="Foto {{ $menu->name }}">
                                        @else
                                            <span class="flex size-12 items-center justify-center rounded-lg bg-[#f3ead8]" aria-hidden="true">🍲</span>
                                        @endif
                                        <span><span class="block font-semibold">{{ $menu->name }}</span><span class="text-xs text-stone-500">{{ $menu->slug }}</span></span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">{{ $menu->category->name }}</td>
                                <td class="px-4 py-3">Rp{{ number_format($menu->price, 0, ',', '.') }}</td>
                                <td class="px-4 py-3">{{ $menu->is_available ? 'Tersedia' : 'Tidak tersedia' }}</td>
                                <td class="px-4 py-3">{{ $menu->order_items_count }}</td>
                                <td class="px-4 py-3 text-right">
                                    <div class="inline-flex items-center gap-3">
                                        <a href="{{ route('admin.menus.edit', $menu) }}" class="font-semibold text-[#7A1F1F] hover:underline">Edit</a>
                                        <form method="POST" action="{{ route('admin.menus.destroy', $menu) }}" onsubmit="return confirm('Hapus menu {{ $menu->name }}?')">
                                            @csrf @method('DELETE')
                                            <button class="font-semibold text-red-700 hover:underline">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="border-t border-[#eadfca] px-4 py-3">{{ $menus->links() }}</div>
        </div>
    @endif
</x-admin-layout>
