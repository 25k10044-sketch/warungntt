<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRoleRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'role' => ['nullable', 'in:admin,pelanggan'],
        ]);
        $users = User::query()
            ->withCount('orders')
            ->when($filters['q'] ?? null, fn ($query, string $search) => $query
                ->where(fn ($query) => $query
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")))
            ->when($filters['role'] ?? null, fn ($query, string $role) => $query->where('role', $role))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'filters'));
    }

    public function updateRole(UserRoleRequest $request, User $user): RedirectResponse
    {
        $role = $request->validated('role');

        if ($user->is($request->user()) && $role !== 'admin') {
            return back()->with('error', 'Anda tidak dapat menurunkan role akun sendiri.');
        }

        if ($user->role === 'admin' && $role !== 'admin' && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Role admin terakhir tidak dapat diubah.');
        }

        $user->update(['role' => $role]);

        return back()->with('success', 'Role pengguna berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri dari halaman ini.');
        }

        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Admin terakhir tidak dapat dihapus.');
        }

        if ($user->orders()->exists()) {
            return back()->with('error', 'Pengguna tidak dapat dihapus karena memiliki riwayat pesanan.');
        }

        DB::transaction(fn () => $user->delete());

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
