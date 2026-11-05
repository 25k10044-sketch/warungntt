<x-admin-layout :title="$menu->exists ? 'Edit Menu' : 'Tambah Menu'">
    <form method="POST" enctype="multipart/form-data" action="{{ $menu->exists ? route('admin.menus.update', $menu) : route('admin.menus.store') }}" class="mx-auto max-w-4xl space-y-6 rounded-2xl border border-[#eadfca] bg-white p-5 sm:p-7">
        @csrf
        @if ($menu->exists) @method('PUT') @endif
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="name" class="font-semibold">Nama menu</label>
                <input id="name" name="name" value="{{ old('name', $menu->name) }}" required maxlength="255" class="mt-1 min-h-11 w-full rounded-lg border-stone-300 focus:border-[#7A1F1F] focus:ring-[#7A1F1F]">
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>
            <div>
                <label for="slug" class="font-semibold">Slug <span class="font-normal text-stone-500">(opsional)</span></label>
                <input id="slug" name="slug" value="{{ old('slug', $menu->slug) }}" maxlength="255" placeholder="Otomatis dari nama menu" class="mt-1 min-h-11 w-full rounded-lg border-stone-300 focus:border-[#7A1F1F] focus:ring-[#7A1F1F]">
                <x-input-error :messages="$errors->get('slug')" class="mt-1" />
            </div>
            <div>
                <label for="category_id" class="font-semibold">Kategori</label>
                <select id="category_id" name="category_id" required class="mt-1 min-h-11 w-full rounded-lg border-stone-300 focus:border-[#7A1F1F] focus:ring-[#7A1F1F]">
                    <option value="">Pilih kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $menu->category_id) == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('category_id')" class="mt-1" />
            </div>
            <div>
                <label for="origin_region" class="font-semibold">Asal daerah</label>
                <input id="origin_region" name="origin_region" value="{{ old('origin_region', $menu->origin_region) }}" required maxlength="255" class="mt-1 min-h-11 w-full rounded-lg border-stone-300 focus:border-[#7A1F1F] focus:ring-[#7A1F1F]">
                <x-input-error :messages="$errors->get('origin_region')" class="mt-1" />
            </div>
            <div>
                <label for="price" class="font-semibold">Harga (rupiah)</label>
                <input id="price" type="number" min="0" max="100000000" name="price" value="{{ old('price', $menu->price) }}" required class="mt-1 min-h-11 w-full rounded-lg border-stone-300 focus:border-[#7A1F1F] focus:ring-[#7A1F1F]">
                <x-input-error :messages="$errors->get('price')" class="mt-1" />
            </div>
            <div>
                <label for="main_ingredients" class="font-semibold">Bahan utama</label>
                <input id="main_ingredients" name="main_ingredients" value="{{ old('main_ingredients', $menu->main_ingredients) }}" required maxlength="2000" class="mt-1 min-h-11 w-full rounded-lg border-stone-300 focus:border-[#7A1F1F] focus:ring-[#7A1F1F]">
                <x-input-error :messages="$errors->get('main_ingredients')" class="mt-1" />
            </div>
        </div>
        <div>
            <label for="description" class="font-semibold">Deskripsi</label>
            <textarea id="description" name="description" rows="3" maxlength="5000" required class="mt-1 w-full rounded-lg border-stone-300 focus:border-[#7A1F1F] focus:ring-[#7A1F1F]">{{ old('description', $menu->description) }}</textarea>
            <x-input-error :messages="$errors->get('description')" class="mt-1" />
        </div>
        <div>
            <label for="story" class="font-semibold">Cerita budaya</label>
            <textarea id="story" name="story" rows="4" maxlength="5000" required class="mt-1 w-full rounded-lg border-stone-300 focus:border-[#7A1F1F] focus:ring-[#7A1F1F]">{{ old('story', $menu->story) }}</textarea>
            <x-input-error :messages="$errors->get('story')" class="mt-1" />
        </div>
        <div class="grid gap-5 sm:grid-cols-[1fr_auto] sm:items-end">
            <div>
                <label for="image" class="font-semibold">Foto menu <span class="font-normal text-stone-500">(JPG, PNG, WebP; maks. 5 MB)</span></label>
                <input id="image" type="file" name="image" accept="image/jpeg,image/png,image/webp" class="mt-2 block min-h-11 w-full text-sm file:mr-4 file:min-h-11 file:rounded-lg file:border-0 file:bg-[#f3ead8] file:px-4 file:font-semibold file:text-[#7A1F1F]">
                <x-input-error :messages="$errors->get('image')" class="mt-1" />
            </div>
            @if ($menu->image)
                <img class="h-24 w-32 rounded-xl object-cover" src="{{ asset('storage/'.$menu->image) }}" alt="Foto saat ini: {{ $menu->name }}">
            @endif
        </div>
        <label class="flex min-h-11 cursor-pointer items-center gap-3">
            <input type="checkbox" name="is_available" value="1" @checked(old('is_available', $menu->exists ? $menu->is_available : true)) class="rounded border-stone-300 text-[#7A1F1F] focus:ring-[#7A1F1F]">
            <span class="font-semibold">Menu tersedia untuk dipesan</span>
        </label>
        <div class="flex flex-wrap justify-end gap-3 border-t border-[#eadfca] pt-5">
            <a href="{{ route('admin.menus.index') }}" class="inline-flex min-h-11 items-center rounded-lg border border-[#cbbd9e] px-5 font-semibold">Batal</a>
            <button class="min-h-11 rounded-lg bg-[#7A1F1F] px-6 font-bold text-white hover:bg-[#5f1717]">{{ $menu->exists ? 'Simpan perubahan' : 'Simpan menu' }}</button>
        </div>
    </form>
</x-admin-layout>
