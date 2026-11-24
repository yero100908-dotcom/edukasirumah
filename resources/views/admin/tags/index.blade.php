@extends('layouts.admin')

@section('title', 'Manajemen Tag Topik — EdukasiRumahan')
@section('page_title', 'Tag & Kata Kunci Teknis')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
    
    <!-- KOLOM KIRI: DAFTAR TAG (7/12) -->
    <div class="lg:col-span-7 bg-white border border-slate-200 rounded-xl shadow-2xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-display font-bold text-sm uppercase tracking-wider text-slate-900">
                Daftar Tag Topik
            </h2>
            <span class="text-xs text-slate-500">{{ $tags->count() }} Tag Terdaftar</span>
        </div>

        <div class="p-6 flex flex-wrap gap-2.5">
            @foreach($tags as $t)
                <div class="inline-flex items-center space-x-2 px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs group hover:border-[#C2410C]/40">
                    <span class="font-medium text-slate-800">#{{ $t->name }}</span>
                    <span class="text-[10px] text-slate-400 bg-white px-1.5 py-0.5 rounded border border-slate-200">
                        {{ $t->articles_count }}
                    </span>
                    <form action="{{ route('admin.tags.destroy', $t->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus tag #{{ $t->name }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-slate-400 hover:text-rose-600 ml-1" title="Hapus Tag">
                            &times;
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>

    <!-- KOLOM KANAN: FORM TAMBAH TAG (5/12) -->
    <div class="lg:col-span-5 bg-white border border-slate-200 rounded-xl p-6 shadow-2xs space-y-4">
        <h3 class="font-display font-bold text-sm uppercase tracking-wider text-slate-900 pb-2 border-b border-slate-100">
            Tambah Tag Baru
        </h3>

        <form action="{{ route('admin.tags.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">
                    Nama Tag <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name') }}"
                    placeholder="Contoh: Waterproofing Dak Beton"
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-[#C2410C]"
                    required>
                @error('name')
                    <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="w-full py-2.5 px-4 bg-[#C2410C] hover:bg-[#9A3412] text-white text-xs font-bold uppercase tracking-wider rounded-lg shadow-xs transition-colors">
                Tambah Tag
            </button>
        </form>
    </div>

</div>
@endsection

