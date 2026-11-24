<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminManagementController extends Controller
{
    /**
     * Display a listing of admin users.
     */
    public function index(): View
    {
        $admins = Admin::withCount('articles')->orderBy('role')->orderBy('name')->get();
        $currentUser = Auth::guard('admin')->user();

        return view('admin.users.index', compact('admins', 'currentUser'));
    }

    /**
     * Store a newly created admin user.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:admins,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'in:superadmin,editor'],
        ], [
            'name.required' => 'Nama lengkap staf admin wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.unique' => 'Email tersebut sudah terdaftar.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
            'role.required' => 'Pilih tingkatan role pengguna.',
        ]);

        Admin::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Akun admin baru "' . $validated['name'] . '" dengan role ' . strtoupper($validated['role']) . ' berhasil ditambahkan.');
    }

    /**
     * Update the specified admin user.
     */
    public function update(Request $request, Admin $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('admins', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', 'in:superadmin,editor'],
        ], [
            'name.required' => 'Nama lengkap staf admin wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.unique' => 'Email tersebut sudah digunakan staf lain.',
            'password.min' => 'Kata sandi baru minimal 6 karakter.',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'Data akun admin "' . $user->name . '" berhasil diperbarui.');
    }

    /**
     * Remove the specified admin user.
     */
    public function destroy(Admin $user): RedirectResponse
    {
        $currentAdmin = Auth::guard('admin')->user();

        if ($currentAdmin && $currentAdmin->id === $user->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun admin yang sedang Anda gunakan saat ini.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Akun admin "' . $name . '" telah berhasil dihapus.');
    }
}

