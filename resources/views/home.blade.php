@extends('layouts.app')

@section('title', 'RumahCraft — Jurnal & Direktori Panduan Konstruksi, Sanitasi & Hunian')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-10">

    <!-- BAR 1: TOPIK HANGAT (TRENDING TICKER ALA PORTAL MEDIA) -->
    <div class="flex items-center space-x-3 py-2.5 px-4 bg-white border border-slate-200 rounded-xl text-xs overflow-x-auto whitespace-nowrap shadow-2xs">
        <div class="flex items-center space-x-1.5 text-[#C2410C] font-extrabold uppercase tracking-wider shrink-0 pr-3 border-r border-slate-200">
            <span class="w-2 h-2 rounded-full bg-[#C2410C] animate-pulse"></span>
            <span>Topik Hangat:</span>
        </div>
        <div class="flex items-center space-x-2">
            @foreach($trendingTags as $tag)
                <a href="{{ route('search', ['tag' => $tag->slug]) }}" class="px-3 py-1 bg-slate-50 hover:bg-orange-50 border border-slate-200 hover:border-[#C2410C]/40 rounded-lg text-slate-700 hover:text-[#C2410C] transition-colors font-medium text-[11px]">
                    #{{ $tag->name }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- SEKSI 2: HEADLINE UTAMA & 5 EDUKASI TERATAS (STRUKTUR TRIAD ALA DETIKCOM DENGAN ESTETIKA ARSITEKTUR) -->
    <section class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- KOLOM KIRI (8/12): HERO UTAMA + 2 SUB-HEADLINE -->
        <div class="lg:col-span-8 space-y-6">
            
            <!-- HERO UTAMA -->
            @if($featuredArticle)
                <article class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-xs hover:border-slate-300 transition-all group">
                    <div class="relative aspect-video sm:aspect-21/9 w-full bg-slate-900 overflow-hidden">
                        @if($featuredArticle->thumbnail_url)
                            <img src="{{ $featuredArticle->thumbnail_url }}" alt="{{ $featuredArticle->title }}" class="w-full h-full object-cover group-hover:scale-[1.02] transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-slate-800 text-slate-500">
                                <span class="text-sm font-mono">Ilustrasi Teknis Lapangan</span>
                            </div>
                        @endif
                        
                        <div class="absolute top-4 left-4">
                            <span class="px-3 py-1.5 bg-[#C2410C] text-white text-[11px] font-bold uppercase tracking-wider rounded-lg shadow-sm">
                                {{ $featuredArticle->category->name }}
                            </span>
                        </div>
                    </div>

                    <div class="p-6 sm:p-8 space-y-4">
                        <div class="flex flex-wrap items-center gap-2 sm:gap-3 text-xs text-slate-500">
                            <span class="font-medium text-slate-700">Ditinjau: {{ $featuredArticle->admin->name ?? 'Redaksi Teknis' }}</span>
                            <span class="text-slate-300">•</span>
                            <span>{{ $featuredArticle->reading_time }} Menit Baca</span>
                            <span class="text-slate-300">•</span>
                            <span class="text-slate-700 font-semibold">{{ number_format($featuredArticle->views_count, 0, ',', '.') }} Pembaca</span>
                        </div>

                        <h1 class="font-editorial text-2xl sm:text-3xl font-bold text-slate-900 group-hover:text-[#C2410C] transition-colors leading-[1.3]">
                            <a href="{{ route('articles.show', $featuredArticle->slug) }}">
                                {{ $featuredArticle->title }}
                            </a>
                        </h1>

                        <p class="text-slate-600 text-sm sm:text-base leading-relaxed line-clamp-2">
                            {{ $featuredArticle->excerpt }}
                        </p>

                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-xs text-slate-400">
                                {{ $featuredArticle->published_at ? $featuredArticle->published_at->locale('id')->isoFormat('D MMMM Y') : 'Terbaru' }}
                            </span>
                            <a href="{{ route('articles.show', $featuredArticle->slug) }}" class="inline-flex items-center space-x-1.5 text-xs font-bold text-[#C2410C] hover:text-[#9A3412] transition-colors">
                                <span>Pelajari Panduan Penuh</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </article>
            @endif

            <!-- 2 SUB-HEADLINE FOKUS BERDAMPINGAN -->
            @if($subFeaturedArticles->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    @foreach($subFeaturedArticles as $sub)
                        <article class="bg-white border border-slate-200 rounded-2xl overflow-hidden hover:border-slate-300 transition-all shadow-2xs group flex flex-col justify-between">
                            <div>
                                <div class="relative aspect-video w-full bg-slate-800 overflow-hidden">
                                    @if($sub->thumbnail_url)
                                        <img src="{{ $sub->thumbnail_url }}" alt="{{ $sub->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    @endif
                                    <div class="absolute top-3 left-3">
                                        <span class="px-2.5 py-1 bg-slate-900/85 backdrop-blur-xs text-white text-[10px] font-bold uppercase rounded-md">
                                            {{ $sub->category->name }}
                                        </span>
                                    </div>
                                </div>

                                <div class="p-5 space-y-2">
                                    <div class="text-[11px] text-slate-400 flex items-center gap-1.5">
                                        <span>{{ $sub->reading_time }} mnt baca</span>
                                        <span>•</span>
                                        <span>{{ number_format($sub->views_count, 0, ',', '.') }} dibaca</span>
                                    </div>
                                    <h2 class="font-editorial font-bold text-base text-slate-900 group-hover:text-[#C2410C] transition-colors line-clamp-2 leading-snug">
                                        <a href="{{ route('articles.show', $sub->slug) }}">
                                            {{ $sub->title }}
                                        </a>
                                    </h2>
                                    <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                        {{ $sub->excerpt }}
                                    </p>
                                </div>
                            </div>

                            <div class="px-5 py-3 bg-slate-50 border-t border-slate-100 text-[11px] text-slate-400 flex items-center justify-between">
                                <span>{{ $sub->published_at ? $sub->published_at->locale('id')->isoFormat('D MMM Y') : '' }}</span>
                                <a href="{{ route('articles.show', $sub->slug) }}" class="font-bold text-[#C2410C] hover:underline">
                                    Baca Panduan &rarr;
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif

        </div>

        <!-- KOLOM KANAN (4/12): 5 EDUKASI TERATAS & RELEVAN (FORMAT RUBRIK TOP 5 ALA DETIKCOM DENGAN SENTUHAN BERSIH) -->
        <div class="lg:col-span-4 bg-white border border-slate-200 rounded-2xl p-6 shadow-2xs space-y-5 sticky top-28">
            
            <div class="border-b border-slate-100 pb-3.5">
                <div class="flex items-center space-x-2">
                    <span class="inline-block w-2.5 h-2.5 rounded-xs bg-[#C2410C]"></span>
                    <h2 class="font-display font-extrabold text-sm sm:text-base tracking-wider uppercase text-slate-900">
                        5 Edukasi Teratas &amp; Relevan
                    </h2>
                </div>
                <p class="text-xs text-slate-500 mt-1">
                    Panduan terpopuler dan paling banyak diaplikasikan di lapangan
                </p>
            </div>

            <!-- List 01 - 05 -->
            <div class="divide-y divide-slate-100">
                @foreach($top5Articles as $index => $topArticle)
                    <article class="py-3.5 first:pt-0 last:pb-0 group">
                        <div class="flex items-start space-x-3.5">
                            <span class="font-display text-2xl font-black text-slate-300 group-hover:text-[#C2410C] transition-colors tabular-nums shrink-0 mt-0.5 w-7">
                                {{ sprintf('%02d', $index + 1) }}
                            </span>
                            
                            <div class="flex-1 space-y-1 min-w-0">
                                <div class="flex items-center gap-2 text-[10px] text-slate-400">
                                    <span class="font-bold text-[#C2410C]">
                                        {{ $topArticle->category->name }}
                                    </span>
                                    <span>•</span>
                                    <span>{{ $topArticle->reading_time }} mnt</span>
                                    <span>•</span>
                                    <span>{{ number_format($topArticle->views_count, 0, ',', '.') }} baca</span>
                                </div>

                                <h3 class="font-display font-bold text-xs sm:text-sm text-slate-900 group-hover:text-[#C2410C] transition-colors leading-snug line-clamp-2">
                                    <a href="{{ route('articles.show', $topArticle->slug) }}">
                                        {{ $topArticle->title }}
                                    </a>
                                </h3>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="pt-3 border-t border-slate-100 text-center">
                <a href="{{ route('search', ['sort' => 'terpopuler']) }}" class="text-xs font-semibold text-slate-600 hover:text-[#C2410C] inline-flex items-center gap-1 transition-colors">
                    <span>Lihat Seluruh Peringkat Panduan</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            <!-- Banner Kredibilitas -->
            <div class="p-4 bg-orange-50/70 border border-orange-100 rounded-xl text-xs text-slate-700 space-y-1.5">
                <span class="font-bold text-[#C2410C] flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-[#C2410C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    Akurasi Standar SNI &amp; Bebas Iklan
                </span>
                <p class="text-[11px] text-slate-600 leading-relaxed">
                    Panduan disusun berdasarkan prinsip mekanika teknik dan campuran material teruji untuk ketahanan struktur jangka panjang.
                </p>
            </div>

        </div>

    </section>

    <!-- SEKSI 3: KANAL SPESIALISASI PEMBAHASAN -->
    <section class="bg-white border border-slate-200 rounded-2xl p-6 shadow-2xs space-y-5">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h2 class="font-display font-bold text-xs uppercase tracking-wider text-slate-900">
                Kanal Spesialisasi Pembahasan
            </h2>
            <span class="text-xs text-slate-400">Pilih rubrik sesuai kebutuhan proyek hunian</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($categories as $category)
                <a href="{{ route('search', ['category' => $category->slug]) }}" class="group p-4 rounded-xl border border-slate-100 hover:border-orange-200 hover:bg-orange-50/40 transition-all flex items-start space-x-3.5">
                    <div class="w-10 h-10 rounded-lg bg-orange-50 text-[#C2410C] flex items-center justify-center shrink-0 group-hover:scale-105 group-hover:bg-[#C2410C] group-hover:text-white transition-all shadow-2xs">
                        {!! $category->icon ?? '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>' !!}
                    </div>
                    <div class="min-w-0">
                        <h3 class="font-bold text-sm text-slate-900 group-hover:text-[#C2410C] transition-colors truncate">
                            {{ $category->name }}
                        </h3>
                        <span class="text-xs text-slate-500 block mt-0.5">
                            {{ $category->articles_count }} Panduan Praktis
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    <!-- SEKSI 4: FEED UTAMA (NEWSFEED STREAM ALA PORTAL MEDIA + SIDEBAR TEKNIS) -->
    <section class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start pt-4">
        
        <!-- FEED ARTIKEL KIRI (8/12): LIST HORIZONTAL ARTIKEL -->
        <div class="lg:col-span-8 space-y-6">
            
            <div class="flex items-center justify-between border-b-2 border-slate-900 pb-3">
                <div class="flex items-center space-x-2">
                    <span class="w-3 h-3 bg-[#C2410C] rounded-xs"></span>
                    <h2 class="font-display font-extrabold text-base tracking-wide uppercase text-slate-900">
                        Aliran Panduan &amp; Jurnal Konstruksi Terbaru
                    </h2>
                </div>
                <a href="{{ route('search') }}" class="text-xs font-semibold text-[#C2410C] hover:underline">
                    Indeks Lengkap &rarr;
                </a>
            </div>

            <!-- Daftar Artikel Horizontal (Format Khas Portal Berita) -->
            <div class="space-y-4">
                @foreach($feedArticles as $article)
                    <article class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 hover:border-slate-300 transition-all shadow-2xs group flex flex-col sm:flex-row gap-5 items-start">
                        
                        <!-- Thumbnail Kiri (Rasio 16:9 yang rapi) -->
                        <a href="{{ route('articles.show', $article->slug) }}" class="relative w-full sm:w-56 aspect-video sm:aspect-16/10 rounded-xl overflow-hidden bg-slate-900 shrink-0 group-hover:opacity-95">
                            @if($article->thumbnail_url)
                                <img src="{{ $article->thumbnail_url }}" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-slate-800 text-slate-500 text-xs font-mono">
                                    Ilustrasi Teknis
                                </div>
                            @endif
                        </a>

                        <!-- Deskripsi Kanan -->
                        <div class="flex-1 space-y-2 min-w-0">
                            <div class="flex items-center space-x-2 text-[11px] text-slate-400">
                                <a href="{{ route('search', ['category' => $article->category->slug]) }}" class="font-bold text-[#C2410C] uppercase tracking-wider hover:underline">
                                    {{ $article->category->name }}
                                </a>
                                <span>•</span>
                                <span>{{ $article->reading_time }} mnt baca</span>
                                <span>•</span>
                                <span>{{ number_format($article->views_count, 0, ',', '.') }} baca</span>
                            </div>

                            <h3 class="font-editorial font-bold text-base sm:text-lg text-slate-900 group-hover:text-[#C2410C] transition-colors leading-snug">
                                <a href="{{ route('articles.show', $article->slug) }}">
                                    {{ $article->title }}
                                </a>
                            </h3>

                            <p class="text-xs sm:text-sm text-slate-600 line-clamp-2 leading-relaxed">
                                {{ $article->excerpt }}
                            </p>

                            <div class="pt-2 flex items-center justify-between text-xs text-slate-400 border-t border-slate-100">
                                <span>{{ $article->published_at ? $article->published_at->locale('id')->isoFormat('D MMMM Y') : '' }}</span>
                                <div class="flex items-center gap-1.5">
                                    @foreach($article->tags->take(2) as $t)
                                        <span class="px-2 py-0.5 rounded-md bg-slate-100 text-[10px] text-slate-600 font-medium">
                                            #{{ $t->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    </article>
                @endforeach
            </div>

            <!-- Tombol Muat Seluruh Panduan -->
            <div class="pt-4 text-center">
                <a href="{{ route('search') }}" class="inline-flex items-center space-x-2 px-6 py-3 bg-white hover:bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-slate-800 shadow-2xs transition-colors">
                    <span>Lihat Seluruh Arsip Panduan Teknis</span>
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </a>
            </div>

        </div>

        <!-- SIDEBAR KANAN (4/12): WIDGET TANYA JAWAB TERBARU & INDEKS TAGS -->
        <aside class="lg:col-span-4 space-y-6">
            
            <!-- WIDGET DISKUSI TERVERIFIKASI -->
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-2xs space-y-4">
                <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                    <h3 class="font-display font-bold text-xs uppercase tracking-wider text-slate-900">
                        Diskusi &amp; Pertanyaan Pembaca
                    </h3>
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                </div>

                <div class="space-y-3.5">
                    @forelse($recentComments as $comment)
                        <div class="p-3.5 bg-slate-50 border border-slate-200/80 rounded-xl space-y-1.5 text-xs">
                            <div class="flex items-center justify-between text-[11px]">
                                <span class="font-bold text-slate-900">{{ $comment->guest_name }}</span>
                                <span class="text-slate-400">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-slate-600 italic line-clamp-2">
                                "{{ $comment->body }}"
                            </p>
                            @if($comment->article)
                                <a href="{{ route('articles.show', $comment->article->slug) }}#kolom-diskusi" class="text-[11px] text-[#C2410C] font-semibold hover:underline block truncate pt-0.5">
                                    Pada: {{ $comment->article->title }}
                                </a>
                            @endif
                        </div>
                    @empty
                        <div class="text-center text-xs text-slate-400 py-4">
                            Belum ada komentar terbaru yang disetujui.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- WIDGET TAG POPULER (TAG CLOUD) -->
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-2xs space-y-3">
                <h3 class="font-display font-bold text-xs uppercase tracking-wider text-slate-900 pb-2 border-b border-slate-100">
                    Indeks Masalah Konstruksi
                </h3>
                <div class="flex flex-wrap gap-1.5">
                    @foreach($trendingTags as $t)
                        <a href="{{ route('search', ['tag' => $t->slug]) }}" class="px-2.5 py-1 rounded-lg bg-slate-50 border border-slate-200 hover:border-[#C2410C] hover:text-[#C2410C] text-[11px] text-slate-700 transition-colors font-medium">
                            #{{ $t->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- WIDGET PRINSIP KERJA & KETAHANAN -->
            <div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white rounded-2xl p-6 shadow-xs space-y-3.5">
                <div class="flex items-center space-x-2 text-orange-400 font-bold text-xs uppercase tracking-wider">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span>Prinsip Zero Rework</span>
                </div>
                <p class="text-xs text-slate-300 leading-relaxed font-sans">
                    Biaya membongkar pondasi retak atau membongkar keramik kopong bisa 5 kali lebih mahal daripada pengerjaan awal yang sesuai kaidah teknik sipil. Bangun dan rawat rumah dengan prosedur presisi.
                </p>
                <div class="pt-2 border-t border-slate-700 text-[11px] text-slate-400 flex items-center justify-between">
                    <span>Standar Mutu Konstruksi</span>
                    <span class="text-orange-400 font-semibold">100% Edukatif</span>
                </div>
            </div>

        </aside>

    </section>

</div>
@endsection
