@extends('layouts.admin')

@section('title', 'Ringkasan Redaksi & Statistik — RumahCraft')
@section('page_title', 'Ringkasan & Statistik Redaksi')

@section('content')
<div class="space-y-8">

    <!-- BARIS 1: 4 KARTU STATISTIK UTAMA -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Total Tayang -->
        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-2xs space-y-2">
            <div class="flex items-center justify-between text-slate-500 text-xs font-semibold uppercase tracking-wider">
                <span>Artikel Tayang</span>
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            </div>
            <div class="flex items-baseline space-x-2">
                <span class="font-display font-extrabold text-3xl text-slate-900">{{ $totalPublished }}</span>
                <span class="text-xs text-slate-500">panduan aktif</span>
            </div>
            <p class="text-[11px] text-slate-400 pt-1 border-t border-slate-100">
                Dapat diakses langsung oleh publik
            </p>
        </div>

        <!-- Total Pembaca (Views Count) -->
        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-2xs space-y-2">
            <div class="flex items-center justify-between text-slate-500 text-xs font-semibold uppercase tracking-wider">
                <span>Total Pembaca</span>
                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            </div>
            <div class="flex items-baseline space-x-2">
                <span class="font-display font-extrabold text-3xl text-slate-900">{{ number_format($totalViews, 0, ',', '.') }}</span>
                <span class="text-xs text-slate-500">kali dibaca</span>
            </div>
            <p class="text-[11px] text-slate-400 pt-1 border-t border-slate-100">
                Akumulasi tayangan seluruh panduan
            </p>
        </div>

        <!-- Menunggu Moderasi -->
        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-2xs space-y-2">
            <div class="flex items-center justify-between text-slate-500 text-xs font-semibold uppercase tracking-wider">
                <span>Menunggu Moderasi</span>
                @if($pendingCommentsCount > 0)
                    <span class="px-2 py-0.5 text-[10px] font-bold bg-amber-100 text-amber-800 rounded-full">Perlu Tindakan</span>
                @endif
            </div>
            <div class="flex items-baseline space-x-2">
                <span class="font-display font-extrabold text-3xl {{ $pendingCommentsCount > 0 ? 'text-amber-600' : 'text-slate-900' }}">{{ $pendingCommentsCount }}</span>
                <span class="text-xs text-slate-500">tanggapan</span>
            </div>
            <p class="text-[11px] text-slate-400 pt-1 border-t border-slate-100">
                Pencegahan spam & verifikasi mutu diskusi
            </p>
        </div>

        <!-- Draft & Terjadwal -->
        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-2xs space-y-2">
            <div class="flex items-center justify-between text-slate-500 text-xs font-semibold uppercase tracking-wider">
                <span>Draf & Antrean</span>
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="flex items-baseline space-x-2">
                <span class="font-display font-extrabold text-3xl text-slate-900">{{ $totalDraft + $totalScheduled }}</span>
                <span class="text-xs text-slate-500">materi draf</span>
            </div>
            <p class="text-[11px] text-slate-400 pt-1 border-t border-slate-100">
                {{ $totalScheduled }} terjadwal, {{ $totalDraft }} draf persiapan
            </p>
        </div>

    </div>

    <!-- BARIS 2: 5 ARTIKEL TERPOPULER & KOMENTAR MENUNGGU MODERASI -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- KOLOM KIRI: 5 PANDUAN PALING BANYAK DIBACA (7/12) -->
        <div class="lg:col-span-7 bg-white border border-slate-200 rounded-xl shadow-2xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="font-display font-bold text-base text-slate-900">
                        5 Panduan Edukasi Terpopuler
                    </h2>
                    <p class="text-xs text-slate-500">Berdasarkan akumulasi views pembaca publik</p>
                </div>
                <a href="{{ route('admin.articles.index') }}" class="text-xs font-semibold text-[#C2410C] hover:underline">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="divide-y divide-slate-100">
                @foreach($topArticles as $idx => $art)
                    <div class="p-4 flex items-center justify-between hover:bg-slate-50/70 transition-colors">
                        <div class="flex items-center space-x-3 min-w-0 pr-4">
                            <span class="font-display font-extrabold text-lg text-slate-300 w-6 shrink-0">
                                {{ sprintf('%02d', $idx + 1) }}
                            </span>
                            <div class="truncate">
                                <a href="{{ route('articles.show', $art->slug) }}" target="_blank" class="font-semibold text-sm text-slate-800 hover:text-[#C2410C] transition-colors truncate block">
                                    {{ $art->title }}
                                </a>
                                <div class="flex items-center space-x-2 text-xs text-slate-400 mt-0.5">
                                    <span class="text-[#C2410C] font-medium">{{ $art->category->name }}</span>
                                    <span>•</span>
                                    <span>{{ $art->published_at ? $art->published_at->format('d/m/Y') : '-' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center space-x-4 shrink-0 text-right">
                            <span class="font-mono text-xs font-bold text-slate-700 bg-slate-100 px-2.5 py-1 rounded">
                                {{ number_format($art->views_count, 0, ',', '.') }} views
                            </span>
                            <a href="{{ route('admin.articles.edit', $art->id) }}" class="p-1.5 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded" title="Edit Artikel">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- KOLOM KANAN: ANTRIAN MODERASI KOMENTAR (5/12) -->
        <div class="lg:col-span-5 bg-white border border-slate-200 rounded-xl shadow-2xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="font-display font-bold text-base text-slate-900">
                        Antrean Moderasi Diskusi
                    </h2>
                    <p class="text-xs text-slate-500">Tanggapan tamu menunggu persetujuan</p>
                </div>
                <a href="{{ route('admin.comments.index') }}" class="text-xs font-semibold text-[#C2410C] hover:underline">
                    Kelola Semua &rarr;
                </a>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($recentPendingComments as $comment)
                    <div class="p-4 space-y-2 hover:bg-slate-50/70 transition-colors">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold text-slate-900">{{ $comment->guest_name }}</span>
                            <span class="text-slate-400 text-[11px]">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-xs text-slate-600 line-clamp-2 italic">
                            "{{ $comment->body }}"
                        </p>
                        <div class="flex items-center justify-between text-[11px] pt-1">
                            <span class="text-slate-400 truncate max-w-[180px]">
                                Pada: {{ $comment->article->title ?? 'Artikel' }}
                            </span>
                            <div class="flex items-center space-x-1.5 shrink-0">
                                <form action="{{ route('admin.comments.approve', $comment->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded text-[11px] font-semibold transition-colors">
                                        Setujui
                                    </button>
                                </form>
                                <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('Hapus komentar ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2.5 py-1 bg-slate-100 hover:bg-rose-50 hover:text-rose-600 text-slate-600 rounded text-[11px] font-semibold transition-colors">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-xs text-slate-400">
                        Semua tanggapan telah dimoderasi. Tidak ada antrean pending.
                    </div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection

