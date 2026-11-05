<x-admin-layout title="Kelola Kategori">
    <section class="rounded-2xl border border-[#eadfca] bg-white p-5 sm:p-6">
        <h2 class="font-serif text-xl font-bold">Tambah kategori</h2>
        <form method="POST" action="{{ route('admin.categories.store') }}" class="mt-4 grid gap-3 sm:grid-cols-[1fr_1fr_auto]">
            @csrf
            <div>
                <label for="new-category-name" class="sr-only">Nama kategori</label>
                <input id="new-category-name" name="name" value="{{ old('name') }}" required maxlength="255" placeholder="Nama kategori" class="min-h-11 w-full rounded-lg border-stone-300 focus:border-[#7A1F1F] focus:ring-[#7A1F1F]">
            </div>
            <div>
                <label for="new-category-slug" class="sr-only">Slug opsional</label>
                <input id="new-category-slug" name="slug" value="{{ old('slug') }}" maxlength="255" placeholder="Slug (otomatis bila kosong)" class="min-h-11 w-full rounded-lg border-stone-300 focus:border-[#7A1F1F] focus:ring-[#7A1F1F]">
            </div>
            <button class="min-h-11 rounded-lg bg-[#7A1F1F] px-5 font-semibold text-white hover:bg-[#5f1717]">Tambah</button>
        </form>
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
        <x-input-error :messages="$errors->get('slug')" class="mt-2" />
    </section>

    <section class="mt-6 overflow-hidden rounded-2xl border border-[#eadfca] bg-white">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[42rem] text-left text-sm">
                <thead class="bg-[#f3ead8]"><tr><th class="px-4 py-3">Nama</th><th class="px-4 py-3">Slug</th><th class="px-4 py-3">Jumlah menu</th><th class="px-4 py-3 text-right">Aksi</th></tr></thead>
                <tbody class="divide-y divide-[#f1eadc]">
                    @forelse ($categories as $category)
                        <tr>
                            <td colspan="4" class="p-0">
                                <form id="category-{{ $category->id }}" method="POST" action="{{ route('admin.categories.update', $category) }}" class="grid items-center gap-3 p-3 sm:grid-cols-[1fr_1fr_8rem_auto]">
                                    @csrf @method('PUT')
                                    <label class="sr-only" for="category-name-{{ $category->id }}">Nama kategori {{ $category->name }}</label>
                                    <input id="category-name-{{ $category->id }}" name="name" value="{{ $category->name }}" required maxlength="255" class="min-h-11 rounded-lg border-stone-300 focus:border-[#7A1F1F] focus:ring-[#7A1F1F]">
                                    <label class="sr-only" for="category-slug-{{ $category->id }}">Slug kategori {{ $category->name }}</label>
                                    <input id="category-slug-{{ $category->id }}" name="slug" value="{{ $category->slug }}" required maxlength="255" class="min-h-11 rounded-lg border-stone-300 focus:border-[#7A1F1F] focus:ring-[#7A1F1F]">
                                    <span class="text-sm text-stone-600">{{ $category->menus_count }} menu</span>
                                    <div class="flex items-center justify-end gap-3">
                                        <button class="font-semibold text-[#7A1F1F] hover:underline">Simpan</button>
                                        <button form="delete-category-{{ $category->id }}" class="font-semibold text-red-700 hover:underline">Hapus</button>
                                    </div>
                                </form>
                                <form id="delete-category-{{ $category->id }}" method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Hapus kategori {{ $category->name }}?')">
                                    @csrf @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-8 text-center text-stone-600">Belum ada kategori.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-admin-layout>
