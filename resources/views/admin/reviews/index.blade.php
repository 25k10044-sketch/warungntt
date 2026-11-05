<x-admin-layout title="Moderasi Ulasan">
    <form method="GET" class="grid gap-3 rounded-2xl border border-[#eadfca] bg-white p-4 sm:grid-cols-[1fr_12rem_auto]">
        <label class="sr-only" for="review-search">Cari ulasan, menu, atau nama pelanggan</label>
        <input id="review-search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Cari ulasan, menu, atau pelanggan..." class="min-h-11 rounded-lg border-stone-300 focus:border-[#7A1F1F] focus:ring-[#7A1F1F]">
        <label class="sr-only" for="rating">Filter rating</label>
        <select id="rating" name="rating" class="min-h-11 rounded-lg border-stone-300 focus:border-[#7A1F1F] focus:ring-[#7A1F1F]">
            <option value="">Semua rating</option>
            @foreach (range(5, 1) as $rating)<option value="{{ $rating }}" @selected(($filters['rating'] ?? '') == $rating)>{{ $rating }} bintang</option>@endforeach
        </select>
        <button class="min-h-11 rounded-lg bg-[#7A1F1F] px-5 font-semibold text-white">Filter</button>
    </form>
    @if ($reviews->isEmpty())
        <div class="mt-6 rounded-2xl border border-dashed border-[#cbbd9e] bg-white p-10 text-center text-stone-600">Belum ada ulasan yang cocok.</div>
    @else
        <div class="mt-6 space-y-4">
            @foreach ($reviews as $review)
                <article class="rounded-2xl border border-[#eadfca] bg-white p-5">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <p class="font-semibold">{{ $review->menu->name }} <span class="ml-1 text-[#7A1F1F]">★ {{ $review->rating }}/5</span></p>
                            <p class="mt-1 text-xs text-stone-500">Oleh {{ $review->user->name }} · {{ $review->created_at->format('d M Y, H:i') }}</p>
                            <p class="mt-3 whitespace-pre-line text-sm leading-6 text-stone-700">{{ $review->comment ?: 'Tanpa komentar.' }}</p>
                        </div>
                        <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" onsubmit="return confirm('Hapus ulasan ini?')">
                            @csrf @method('DELETE')
                            <button class="min-h-11 rounded-lg border border-red-200 px-4 font-semibold text-red-700 hover:bg-red-50">Hapus ulasan</button>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>
        <div class="mt-6">{{ $reviews->links() }}</div>
    @endif
</x-admin-layout>
