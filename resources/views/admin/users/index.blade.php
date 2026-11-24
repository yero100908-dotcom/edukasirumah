@extends('layouts.admin')

@section('title', 'Manajemen Pengguna & Role Admin — EdukasiRumahan')
@section('page_title', 'Kelola Pengguna & Role Admin')

@section('content')
<div class="space-y-8" x-data="{ editModalOpen: false, editUser: { id: '', name: '', email: '', role: 'editor' } }">

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- KOLOM KIRI: DAFTAR PENGGUNA ADMIN (8/12) -->
        <div class="lg:col-span-8 bg-white border border-slate-200 rounded-xl shadow-2xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="font-display font-bold text-sm uppercase tracking-wider text-slate-900">
                        Daftar Staf Redaksi & Administrator
                    </h2>
                    <p class="text-xs text-slate-500">Staf yang memiliki akses ke panel penulisan dan moderasi</p>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 bg-slate-100 text-slate-700 rounded-md">
                    {{ $admins->count() }} Akun Terdaftar
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-6">Nama &amp; Email</th>
                            <th class="py-3 px-4">Tingkatan Role</th>
                            <th class="py-3 px-4 text-center">Artikel Ditulis</th>
                            <th class="py-3 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($admins as $admin)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-100 border border-slate-300 flex items-center justify-center font-bold text-slate-700 text-xs shrink-0">
                                            {{ substr($admin->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <span class="font-bold text-sm text-slate-900 block">
                                                {{ $admin->name }}
                                                @if($currentUser && $currentUser->id === $admin->id)
                                                    <span class="text-[10px] text-emerald-600 bg-emerald-50 px-1.5 py-0.2 rounded border border-emerald-200 ml-1 font-semibold">Anda</span>
                                                @endif
                                            </span>
                                            <span class="text-slate-400 font-mono text-[11px]">{{ $admin->email }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-4 px-4 whitespace-nowrap">
                                    @if($admin->role === 'superadmin')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-orange-50 text-[#C2410C] border border-orange-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#C2410C] mr-1.5"></span>
                                            Superadmin
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-sky-50 text-sky-700 border border-sky-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-sky-500 mr-1.5"></span>
                                            Editor
                                        </span>
                                    @endif
                                </td>

                                <td class="py-4 px-4 text-center font-mono font-semibold text-slate-700 whitespace-nowrap">
                                    {{ $admin->articles_count }} Panduan
                                </td>

                                <td class="py-4 px-6 text-right whitespace-nowrap space-x-1.5">
                                    <button 
                                        type="button" 
                                        @click="editUser = { id: '{{ $admin->id }}', name: '{{ addslashes($admin->name) }}', email: '{{ $admin->email }}', role: '{{ $admin->role }}' }; editModalOpen = true;"
                                        class="p-1.5 text-slate-400 hover:text-slate-800 hover:bg-slate-100 rounded inline-block" 
                                        title="Ubah Data Pengguna">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>

                                    @if($currentUser && $currentUser->id !== $admin->id)
                                        <form action="{{ route('admin.users.destroy', $admin->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun admin {{ $admin->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded inline-block" title="Hapus Akun">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Panduan Perbedaan Role -->
            <div class="p-4 bg-slate-50 border-t border-slate-200 text-xs text-slate-600 space-y-1">
                <span class="font-bold text-slate-800 block">Panduan Hak Akses Role:</span>
                <ul class="list-disc list-inside space-y-0.5 text-[11px] text-slate-500">
                    <li><strong>Superadmin:</strong> Memiliki akses penuh mengelola pengguna admin, kategori, tag, moderasi komentar, dan seluruh artikel.</li>
                    <li><strong>Editor:</strong> Berfokus pada pembuatan berita/panduan teknis baru, pengunggahan materi, dan peninjauan komentar.</li>
                </ul>
            </div>
        </div>

        <!-- KOLOM KANAN: FORM TAMBAH ADMIN BARU (4/12) -->
        <div class="lg:col-span-4 bg-white border border-slate-200 rounded-xl p-6 shadow-2xs space-y-4">
            <h3 class="font-display font-bold text-sm uppercase tracking-wider text-slate-900 pb-2 border-b border-slate-100">
                Tambah Staf Admin / Editor
            </h3>

            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">
                        Nama Lengkap <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        value="{{ old('name') }}"
                        placeholder="Contoh: Budi Santoso, S.T."
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-[#C2410C]"
                        required>
                    @error('name')
                        <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">
                        Alamat Email Login <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        value="{{ old('email') }}"
                        placeholder="staf@edukasirumahan.id"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-[#C2410C]"
                        required>
                    @error('email')
                        <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">
                        Kata Sandi Awal <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        placeholder="Minimal 6 karakter"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-[#C2410C]"
                        required>
                    @error('password')
                        <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="role" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">
                        Tingkatan Role <span class="text-rose-500">*</span>
                    </label>
                    <select 
                        name="role" 
                        id="role" 
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium text-slate-800 focus:outline-none focus:ring-1 focus:ring-[#C2410C]"
                        required>
                        <option value="editor" {{ old('role') === 'editor' ? 'selected' : '' }}>Editor (Penulis Panduan Teknis)</option>
                        <option value="superadmin" {{ old('role') === 'superadmin' ? 'selected' : '' }}>Superadmin (Akses Penuh)</option>
                    </select>
                </div>

                <button type="submit" class="w-full py-2.5 px-4 bg-[#C2410C] hover:bg-[#9A3412] text-white text-xs font-bold uppercase tracking-wider rounded-lg shadow-xs transition-colors">
                    Daftarkan Akun
                </button>
            </form>
        </div>

    </div>

    <!-- MODAL EDIT PENGGUNA ADMIN (Alpine.js) -->
    <div 
        x-show="editModalOpen" 
        x-cloak 
        class="fixed inset-0 z-50 overflow-y-auto" 
        role="dialog" 
        aria-modal="true">
        
        <div class="min-h-screen px-4 text-center flex items-center justify-center">
            <div 
                x-show="editModalOpen" 
                @click="editModalOpen = false" 
                class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity"></div>

            <div 
                x-show="editModalOpen" 
                class="relative bg-white rounded-xl max-w-md w-full text-left overflow-hidden shadow-2xl border border-slate-200 z-10 p-6 space-y-4">
                
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="font-display font-bold text-sm text-slate-900 uppercase tracking-wider">
                        Perbarui Akun Admin
                    </h3>
                    <button type="button" @click="editModalOpen = false" class="text-slate-400 hover:text-slate-600 text-lg">&times;</button>
                </div>

                <form :action="'{{ url('admin/users') }}/' + editUser.id" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">
                            Nama Lengkap
                        </label>
                        <input 
                            type="text" 
                            name="name" 
                            x-model="editUser.name"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800"
                            required>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">
                            Alamat Email
                        </label>
                        <input 
                            type="email" 
                            name="email" 
                            x-model="editUser.email"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800"
                            required>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">
                            Ubah Kata Sandi (Kosongkan bila tidak diganti)
                        </label>
                        <input 
                            type="password" 
                            name="password" 
                            placeholder="Biarkan kosong jika tidak ingin mengubah sandi"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">
                            Tingkatan Role
                        </label>
                        <select 
                            name="role" 
                            x-model="editUser.role"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium text-slate-800">
                            <option value="editor">Editor (Penulis Panduan Teknis)</option>
                            <option value="superadmin">Superadmin (Akses Penuh)</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-end space-x-2 pt-2">
                        <button type="button" @click="editModalOpen = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 rounded-lg text-xs font-semibold text-slate-600">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-[#C2410C] hover:bg-[#9A3412] text-white rounded-lg text-xs font-semibold">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

</div>
@endsection

