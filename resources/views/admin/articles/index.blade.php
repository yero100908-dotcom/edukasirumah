@extends('layouts.admin')

@section('title', 'Daftar Artikel & Panduan Teknis — EdukasiRumahan')
@section('page_title', 'Manajemen Artikel & Panduan')

@section('content')
<div class="space-y-6">

    <!-- BAR FILTER & PENCARIAN -->
    <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-2xs">
        <form action="{{ route('admin.articles.index') }}" method="GET" class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
            
            <div class="flex-1 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                <!-- Search Input -->
                <div class="relative flex-1">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input 
                        type="text" 
                        name="q" 
                        value="{{ request('q') }}" 
                        placeholder="Cari judul atau ringkasan..." 
                        class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-[#C2410C]">
                </div>

                <!-- Status Filter -->
                <select 
                    name="status" 
                    onchange="this.form.submit()" 
                    class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-1 focus:ring-[#C2410C]">
                    <option value="">Semua Status</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Tayang (Published)</option>
                    <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Terjadwal (Scheduled)</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draf (Draft)</option>
                </select>

                <!-- Category Filter -->
                <select 
                    name="category_id" 
                    onchange="this.form.submit()" 
                    class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-1 focus:ring-[#C2410C]">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center space-x-2">
                @if(request()->anyFilled(['q', 'status', 'category_id']))
                    <a href="{{ route('admin.articles.index') }}" class="px-3 py-2 text-xs font-semibold text-rose-600 hover:underline">
                        Reset Filter
                    </a>
                @endif
                <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-lg transition-colors">
                    Terapkan
                </button>
            </div>

        </form>
    </div>

    <!-- TABEL ARTIKEL -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-6">Panduan & Kategori</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-center">Pembaca</th>
                        <th class="py-3.5 px-4 text-center">Komentar</th>
                        <th class="py-3.5 px-4">Tanggal Tayang</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($articles as $art)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <!-- Judul & Kategori -->
                            <td class="py-4 px-6 max-w-md">
                                <div class="space-y-1">
                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold bg-orange-50 text-[#C2410C] border border-orange-200/50">
                                        {{ $art->category->name }}
                                    </span>
                                    <a href="{{ route('admin.articles.edit', $art->id) }}" class="font-bold text-sm text-slate-900 hover:text-[#C2410C] transition-colors block line-clamp-1">
                                        {{ $art->title }}
                                    </a>
                                    <div class="flex items-center space-x-2 text-[11px] text-slate-400">
                                        <span>Penulis: {{ $art->admin->name ?? 'Redaksi' }}</span>
                                        <span>•</span>
                                        <span>{{ $art->reading_time }} mnt baca</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Status Badge -->
                            <td class="py-4 px-4 whitespace-nowrap">
                                @if($art->status === 'published')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                        Tayang
                                    </span>
                                @elseif($art->status === 'scheduled')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-1.5"></span>
                                        Terjadwal
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400 mr-1.5"></span>
                                        Draf
                                    </span>
                                @endif
                            </td>

                            <!-- Pembaca -->
                            <td class="py-4 px-4 text-center font-mono font-medium text-slate-700 whitespace-nowrap">
                                {{ number_format($art->views_count, 0, ',', '.') }}
                            </td>

                            <!-- Komentar -->
                            <td class="py-4 px-4 text-center whitespace-nowrap">
                                <span class="font-medium text-slate-700">{{ $art->comments_count }}</span>
                                @if($art->comments_count > $art->approved_comments_count)
                                    <span class="text-[10px] text-amber-600 font-semibold block">
                                        ({{ $art->comments_count - $art->approved_comments_count }} pending)
                                    </span>
                                @endif
                            </td>

                            <!-- Tanggal Publikasi -->
                            <td class="py-4 px-4 whitespace-nowrap text-slate-500">
                                {{ $art->published_at ? $art->published_at->format('d/m/Y H:i') : '-' }}
                            </td>

                            <!-- Aksi -->
                            <td class="py-4 px-6 text-right whitespace-nowrap space-x-2">
                                @if($art->status === 'published')
                                    <a href="{{ route('articles.show', $art->slug) }}" target="_blank" class="p-1.5 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded inline-block" title="Pratinjau Publik">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                    </a>
                                @endif

                                <a href="{{ route('admin.articles.edit', $art->id) }}" class="p-1.5 text-slate-400 hover:text-[#C2410C] hover:bg-slate-100 rounded inline-block" title="Edit Artikel">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>

                                <form action="{{ route('admin.articles.destroy', $art->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus panduan ini secara permanen?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded" title="Hapus Artikel">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                Tidak ada artikel yang sesuai kriteria pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginasi -->
        <div class="p-4 border-t border-slate-100">
            {{ $articles->links() }}
        </div>
    </div>

</div>
@endsection

