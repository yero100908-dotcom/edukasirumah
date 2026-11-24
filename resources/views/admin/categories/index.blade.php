@extends('layouts.admin')

@section('title', 'Manajemen Kategori Topik — RumahCraft')
@section('page_title', 'Kategori Pembahasan Bangunan')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
    
    <!-- KOLOM KIRI: DAFTAR KATEGORI (7/12) -->
    <div class="lg:col-span-7 bg-white border border-slate-200 rounded-xl shadow-2xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-display font-bold text-sm uppercase tracking-wider text-slate-900">
                Daftar Kategori Aktif
            </h2>
            <span class="text-xs text-slate-500">{{ $categories->count() }} Kategori</span>
        </div>

        <div class="divide-y divide-slate-100">
            @foreach($categories as $category)
                <div class="p-5 flex items-start justify-between gap-4 hover:bg-slate-50/70 transition-colors">
                    <div class="flex items-start space-x-3.5">
                        <div class="w-10 h-10 rounded-lg bg-orange-50 text-[#2A5C82] flex items-center justify-center shrink-0 mt-0.5">
                            {!! $category->icon ?? '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>' !!}
                        </div>
                        <div class="space-y-1">
                            <h3 class="font-bold text-sm text-slate-900">
                                {{ $category->name }}
                            </h3>
                            <p class="text-xs text-slate-500 leading-relaxed max-w-sm">
                                {{ $category->description }}
                            </p>
                            <div class="text-[11px] text-slate-400 font-mono pt-1">
                                <span>slug: {{ $category->slug }}</span> • <span>{{ $category->articles_count }} artikel</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center space-x-2 shrink-0">
                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded" title="Hapus Kategori">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- KOLOM KANAN: FORM TAMBAH KATEGORI (5/12) -->
    <div class="lg:col-span-5 bg-white border border-slate-200 rounded-xl p-6 shadow-2xs space-y-4">
        <h3 class="font-display font-bold text-sm uppercase tracking-wider text-slate-900 pb-2 border-b border-slate-100">
            Tambah Kategori Baru
        </h3>

        <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">
                    Nama Kategori <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name') }}"
                    placeholder="Contoh: Kelistrikan & Pencahayaan"
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-[#2A5C82]"
                    required>
                @error('name')
                    <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">
                    Deskripsi Singkat <span class="text-rose-500">*</span>
                </label>
                <textarea 
                    name="description" 
                    id="description" 
                    rows="3" 
                    placeholder="Uraian cakupan teknis yang dibahas dalam rubrik ini..."
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-[#2A5C82]"
                    required>{{ old('description') }}</textarea>
                @error('description')
                    <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="icon" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">
                    Kode Ikon SVG (Lucide / Heroicons)
                </label>
                <textarea 
                    name="icon" 
                    id="icon" 
                    rows="3" 
                    placeholder="<svg ...></svg> (Gunakan tag SVG Lucide murni, bukan emoji)"
                    class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-lg font-mono text-[11px] text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-[#2A5C82]">{{ old('icon') }}</textarea>
                <span class="text-[11px] text-slate-400 block mt-0.5">Bila dikosongkan, ikon default arsitektur akan digunakan.</span>
            </div>

            <button type="submit" class="w-full py-2.5 px-4 bg-[#2A5C82] hover:bg-[#1E4360] text-white text-xs font-bold uppercase tracking-wider rounded-lg shadow-xs transition-colors">
                Simpan Kategori
            </button>
        </form>
    </div>

</div>
@endsection

