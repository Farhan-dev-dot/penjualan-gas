<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ManajemenAdminController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $admins = User::query()
            ->where('role', 'admin')
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('telepon', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.manajemenadmin', compact('admins', 'search'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'telepon' => ['nullable', 'string', 'max:30'],
            'alamat' => ['required', 'string', 'max:1000'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:manager,admin,user'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'telepon' => $validated['telepon'] ?? null,
            'alamat' => $validated['alamat'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'status' => 'aktif',
        ]);

        return redirect()->route('manager.manajemen-admin')
            ->with('success', 'Akun baru berhasil ditambahkan.');
    }

    public function updateStatus(Request $request, User $admin)
    {
        abort_unless($admin->role === 'admin', 404);

        $validated = $request->validate([
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        $admin->update(['status' => $validated['status']]);

        return redirect()->route('manager.manajemen-admin')
            ->with('success', 'Status akun admin berhasil diperbarui.');
    }

    public function destroy(User $admin)
    {
        abort_unless($admin->role === 'admin', 404);

        $admin->delete();

        return redirect()->route('manager.manajemen-admin')
            ->with('success', 'Akun admin berhasil dihapus.');
    }
}
