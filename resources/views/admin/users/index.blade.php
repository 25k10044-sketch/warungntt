<x-admin-layout title="Kelola Pengguna">
    <form method="GET" class="grid gap-3 rounded-2xl border border-[#eadfca] bg-white p-4 sm:grid-cols-[1fr_14rem_auto]">
        <label class="sr-only" for="user-search">Cari nama atau email</label>
        <input id="user-search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Cari nama atau email..." class="min-h-11 rounded-lg border-stone-300 focus:border-[#7A1F1F] focus:ring-[#7A1F1F]">
        <label class="sr-only" for="user-role-filter">Filter role</label>
        <select id="user-role-filter" name="role" class="min-h-11 rounded-lg border-stone-300 focus:border-[#7A1F1F] focus:ring-[#7A1F1F]">
            <option value="">Semua role</option>
            <option value="pelanggan" @selected(($filters['role'] ?? '') === 'pelanggan')>Pelanggan</option>
            <option value="admin" @selected(($filters['role'] ?? '') === 'admin')>Admin</option>
        </select>
        <button class="min-h-11 rounded-lg bg-[#7A1F1F] px-5 font-semibold text-white">Filter</button>
    </form>
    <div class="mt-6 overflow-hidden rounded-2xl border border-[#eadfca] bg-white">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[58rem] text-left text-sm">
                <thead class="bg-[#f3ead8]"><tr><th class="px-4 py-3">Pengguna</th><th class="px-4 py-3">Role</th><th class="px-4 py-3">Pesanan</th><th class="px-4 py-3">Terdaftar</th><th class="px-4 py-3 text-right">Aksi</th></tr></thead>
                <tbody class="divide-y divide-[#f1eadc]">
                    @forelse ($users as $user)
                        <tr>
                            <td class="px-4 py-3"><span class="block font-semibold">{{ $user->name }}</span><span class="text-xs text-stone-500">{{ $user->email }}</span></td>
                            <td class="px-4 py-3">{{ ucfirst($user->role) }}</td>
                            <td class="px-4 py-3">{{ $user->orders_count }}</td>
                            <td class="px-4 py-3">{{ $user->created_at->format('d M Y') }}</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap items-center justify-end gap-3">
                                    <form method="POST" action="{{ route('admin.users.role', $user) }}" class="flex items-center gap-2">
                                        @csrf @method('PATCH')
                                        <label class="sr-only" for="role-{{ $user->id }}">Role {{ $user->name }}</label>
                                        <select id="role-{{ $user->id }}" name="role" class="min-h-10 rounded-lg border-stone-300 text-sm focus:border-[#7A1F1F] focus:ring-[#7A1F1F]">
                                            <option value="pelanggan" @selected($user->role === 'pelanggan')>Pelanggan</option>
                                            <option value="admin" @selected($user->role === 'admin')>Admin</option>
                                        </select>
                                        <button class="min-h-10 rounded-lg border border-[#7A1F1F] px-3 font-semibold text-[#7A1F1F]">Simpan</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Hapus akun {{ $user->name }}?')">
                                        @csrf @method('DELETE')
                                        <button class="min-h-10 px-2 font-semibold text-red-700 hover:underline">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-8 text-center text-stone-600">Tidak ada pengguna yang cocok.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-[#eadfca] px-4 py-3">{{ $users->links() }}</div>
    </div>
</x-admin-layout>
