@extends('layouts.app')

@section('title', $article->title . ' — EdukasiRumahan')
@section('meta_description', Str::limit($article->excerpt, 155))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-8 py-8 sm:py-12">

    <!-- BREADCRUMB NAVIGASI -->
    <nav class="flex items-center space-x-2 text-xs text-slate-500 mb-6 font-medium overflow-x-auto whitespace-nowrap pb-2">
        <a href="{{ route('home') }}" class="hover:text-[#C2410C] transition-colors">Beranda</a>
        <span class="text-slate-300">/</span>
        <a href="{{ route('search', ['category' => $article->category->slug]) }}" class="hover:text-[#C2410C] transition-colors">
            {{ $article->category->name }}
        </a>
        <span class="text-slate-300">/</span>
        <span class="text-slate-700 truncate max-w-xs sm:max-w-md">{{ $article->title }}</span>
    </nav>

    <!-- HEADER ARTIKEL EDITORIAL -->
    <header class="max-w-4xl space-y-5 pb-8 border-b border-slate-200">
        
        <!-- Kategori & Estimasi Baca -->
        <div class="flex flex-wrap items-center gap-3 text-xs">
            <a href="{{ route('search', ['category' => $article->category->slug]) }}" class="px-3 py-1 rounded-full bg-orange-50 text-[#C2410C] font-semibold border border-orange-200/60 uppercase tracking-wide text-[11px] hover:bg-orange-100 transition-colors">
                {{ $article->category->name }}
            </a>
            <span class="text-slate-300">•</span>
            <span class="text-slate-500 font-medium">Estimasi Baca: {{ $article->reading_time }} Menit</span>
            <span class="text-slate-300">•</span>
            <span class="text-slate-500 font-medium">{{ number_format($article->views_count, 0, ',', '.') }} Kali Dibaca</span>
        </div>

        <!-- Judul Artikel Otoritatif -->
        <h1 class="font-editorial text-2xl sm:text-4xl lg:text-[2.65rem] font-bold text-slate-900 leading-[1.25] tracking-tight">
            {{ $article->title }}
        </h1>

        <!-- Excerpt Ringkasan Masalah & Solusi -->
        <div class="p-4 sm:p-5 bg-white border-l-4 border-[#C2410C] rounded-r-lg shadow-2xs border-y border-r border-slate-200">
            <p class="text-slate-700 text-base sm:text-lg font-editorial italic leading-relaxed">
                "{{ $article->excerpt }}"
            </p>
        </div>

        <!-- Penulis & Aksi Bagikan -->
        <div class="pt-3 flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-xs text-slate-500">
            <div class="flex items-center space-x-3">
                <span class="w-10 h-10 rounded-full bg-slate-200 border border-slate-300 flex items-center justify-center font-bold text-slate-700 text-sm">
                    {{ substr($article->admin->name ?? 'BS', 0, 2) }}
                </span>
                <div>
                    <span class="font-semibold text-slate-900 block text-sm">{{ $article->admin->name ?? 'Ir. Bambang Sudibyo, M.T.' }}</span>
                    <span class="text-slate-500">Ditinjau Redaksi Teknis • Diterbitkan {{ $article->published_at ? $article->published_at->locale('id')->isoFormat('D MMMM Y') : 'Terbaru' }}</span>
                </div>
            </div>

            <!-- Tombol Aksi Salin & Bagikan -->
            <div class="flex items-center space-x-2">
                <button 
                    type="button" 
                    @click="navigator.clipboard.writeText(window.location.href); showToast('Tautan panduan berhasil disalin!')"
                    class="inline-flex items-center space-x-1.5 px-3 py-1.5 bg-white border border-slate-200 hover:border-slate-300 rounded-lg text-slate-700 hover:text-slate-900 font-medium transition-all shadow-2xs">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    <span>Salin Tautan</span>
                </button>

                <a 
                    href="https://api.whatsapp.com/send?text={{ urlencode($article->title . ' — Baca panduan teknisnya di: ' . url()->current()) }}" 
                    target="_blank" 
                    class="inline-flex items-center space-x-1.5 px-3 py-1.5 bg-emerald-50 border border-emerald-200 text-emerald-800 hover:bg-emerald-100 rounded-lg font-medium transition-all shadow-2xs">
                    <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.969.586 1.761.88 2.796.88h.008c3.18 0 5.767-2.587 5.767-5.766.001-3.187-2.575-5.77-5.775-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.101.005.232-.039.362.275.144.346.491 1.199.534 1.286.043.087.072.188.014.303-.058.116-.087.188-.173.289l-.26.303c-.087.087-.179.182-.077.357.101.173.453.747.971 1.209.667.594 1.229.779 1.403.865.174.087.275.072.376-.043.101-.116.433-.506.549-.679.116-.173.231-.144.39-.087s1.011.477 1.184.563.289.13.332.202c.043.073.043.419-.101.824z"/>
                    </svg>
                    <span>Bagikan WhatsApp</span>
                </a>
            </div>
        </div>

    </header>

    <!-- KONTEN UTAMA DENGAN SIDEBAR 5 EDUKASI TERATAS -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 pt-10">
        
        <!-- KOLOM KIRI: KONTEN ARTIKEL (MAKS 72ch PADA DESKTOP) -->
        <main class="lg:col-span-8">
            <article class="max-w-[72ch]">

                <!-- DAFTAR ISI OTOMATIS (TABLE OF CONTENTS) -->
                @if(count($toc) > 0)
                    <div class="mb-10 p-5 sm:p-6 bg-white border border-slate-200 rounded-xl shadow-2xs" x-data="{ tocOpen: true }">
                        <div class="flex items-center justify-between cursor-pointer select-none" @click="tocOpen = !tocOpen">
                            <div class="flex items-center space-x-2.5">
                                <svg class="w-4 h-4 text-[#C2410C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                </svg>
                                <span class="font-display font-bold text-sm text-slate-900 tracking-wide uppercase">
                                    Daftar Isi Panduan
                                </span>
                            </div>
                            <button type="button" class="text-xs text-slate-400 hover:text-slate-600 font-medium">
                                <span x-show="tocOpen">Sembunyikan</span>
                                <span x-show="!tocOpen" x-cloak>Tampilkan</span>
                            </button>
                        </div>

                        <div x-show="tocOpen" x-transition class="mt-4 pt-4 border-t border-slate-100 text-sm">
                            <ol class="space-y-2">
                                @foreach($toc as $item)
                                    <li class="{{ $item['level'] === 3 ? 'ml-5 text-slate-500 text-[13px]' : 'font-medium text-slate-700' }}">
                                        <a href="#{{ $item['id'] }}" class="hover:text-[#C2410C] hover:underline transition-colors flex items-start gap-2">
                                            <span class="text-slate-300">&bull;</span>
                                            <span>{{ $item['title'] }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                @endif

                <!-- BODY ISI ARTIKEL (PROSA BERBOBOT TEKNIS) -->
                <div class="article-prose">
                    {!! $parsedContent !!}
                </div>

                <!-- TAGS TOPIK ARTIKEL -->
                <div class="mt-12 pt-6 border-t border-slate-200">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-3">
                        Topik & Kata Kunci Teknis Terkait:
                    </span>
                    <div class="flex flex-wrap gap-2">
                        @foreach($article->tags as $tag)
                            <a href="{{ route('search', ['tag' => $tag->slug]) }}" class="px-3 py-1 bg-white border border-slate-200/90 rounded-md text-xs text-slate-700 hover:border-[#C2410C] hover:text-[#C2410C] transition-colors font-medium">
                                #{{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- ARTIKEL TERKAIT (3 PANDUAN RELEVAN) -->
                @if($relatedArticles->count() > 0)
                    <div class="mt-16 pt-10 border-t border-slate-200 space-y-6">
                        <div class="flex items-center space-x-2">
                            <span class="w-2 h-2 rounded-full bg-[#C2410C]"></span>
                            <h3 class="font-editorial text-xl font-bold text-slate-900">
                                Rekomendasi Panduan Terkait Lainnya
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                            @foreach($relatedArticles as $rel)
                                <a href="{{ route('articles.show', $rel->slug) }}" class="group bg-white border border-slate-200 p-4 rounded-lg hover:border-slate-300 transition-all flex flex-col justify-between">
                                    <div class="space-y-2">
                                        <span class="text-[10px] font-semibold text-[#C2410C] uppercase tracking-wider">
                                            {{ $rel->category->name }}
                                        </span>
                                        <h4 class="font-display font-bold text-sm text-slate-900 group-hover:text-[#C2410C] transition-colors line-clamp-2 leading-snug">
                                            {{ $rel->title }}
                                        </h4>
                                    </div>
                                    <span class="text-[11px] text-slate-400 mt-3 pt-3 border-t border-slate-50">
                                        {{ $rel->reading_time }} menit baca
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- KOLOM DISKUSI & PERTANYAAN PEMBACA -->
                <section id="kolom-diskusi" class="mt-16 pt-10 border-t border-slate-200 space-y-8">
                    
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-editorial text-xl font-bold text-slate-900">
                                Kolom Diskusi & Tanya Jawab Teknis
                            </h3>
                            <p class="text-xs text-slate-500 mt-1">
                                Sampaikan pertanyaan konstruksi atau bagikan pengalaman Anda terkait topik di atas.
                            </p>
                        </div>
                        <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-xs font-semibold rounded-md">
                            {{ $comments->count() }} Tanggapan
                        </span>
                    </div>

                    <!-- Notifikasi Komentar Berhasil Terkirim -->
                    @if(session('comment_success'))
                        <div class="p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm flex items-start space-x-3">
                            <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <strong class="font-semibold block mb-0.5">Tanggapan Diterima</strong>
                                {{ session('comment_success') }}
                            </div>
                        </div>
                    @endif

                    <!-- Form Komentar Tanpa Login -->
                    <form action="{{ route('comments.store', $article->id) }}" method="POST" class="bg-white border border-slate-200 p-6 sm:p-7 rounded-xl shadow-2xs space-y-4">
                        @csrf
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="guest_name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                    Nama Lengkap <span class="text-rose-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="guest_name" 
                                    id="guest_name" 
                                    value="{{ old('guest_name') }}"
                                    placeholder="Contoh: Hendra Kusuma" 
                                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#C2410C]/20 focus:border-[#C2410C]"
                                    required>
                                @error('guest_name')
                                    <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="guest_email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                    Alamat Email <span class="text-rose-500">*</span>
                                </label>
                                <input 
                                    type="email" 
                                    name="guest_email" 
                                    id="guest_email" 
                                    value="{{ old('guest_email') }}"
                                    placeholder="alamat@email.com (tidak dipublikasikan)" 
                                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#C2410C]/20 focus:border-[#C2410C]"
                                    required>
                                @error('guest_email')
                                    <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="body" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                Pertanyaan atau Catatan Teknis <span class="text-rose-500">*</span>
                            </label>
                            <textarea 
                                name="body" 
                                id="body" 
                                rows="3" 
                                placeholder="Tuliskan kendala di lapangan atau detail bahan yang ingin Anda tanyakan..."
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#C2410C]/20 focus:border-[#C2410C]"
                                required>{{ old('body') }}</textarea>
                            @error('body')
                                <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-2">
                            <span class="text-[11px] text-slate-400">
                                Demi menjaga kualitas diskusi teknis, komentar baru akan melalui moderasi redaksi sebelum tayang.
                            </span>
                            <button type="submit" class="px-5 py-2.5 bg-[#C2410C] hover:bg-[#9A3412] text-white font-semibold text-xs rounded-lg transition-colors shadow-2xs self-end sm:self-auto shrink-0">
                                Kirim Pertanyaan
                            </button>
                        </div>
                    </form>

                    <!-- Daftar Komentar yang Telah Disetujui (Approved) -->
                    <div class="space-y-4 pt-4">
                        @forelse($comments as $comment)
                            <div class="p-5 bg-white border border-slate-200 rounded-xl space-y-2">
                                <div class="flex items-center justify-between text-xs">
                                    <div class="flex items-center space-x-2">
                                        <span class="w-6 h-6 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-600 text-[10px]">
                                            {{ substr($comment->guest_name, 0, 1) }}
                                        </span>
                                        <span class="font-bold text-slate-900">{{ $comment->guest_name }}</span>
                                        <span class="text-emerald-700 font-medium text-[10px] bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">
                                            Terverifikasi
                                        </span>
                                    </div>
                                    <span class="text-slate-400 text-[11px]">
                                        {{ $comment->created_at->locale('id')->diffForHumans() }}
                                    </span>
                                </div>
                                <p class="text-sm text-slate-700 leading-relaxed font-sans pl-8">
                                    {{ $comment->body }}
                                </p>
                            </div>
                        @empty
                            <div class="p-8 text-center bg-white border border-dashed border-slate-200 rounded-xl text-slate-400 text-xs">
                                Belum ada diskusi pada artikel ini. Jadilah yang pertama mengajukan pertanyaan teknis!
                            </div>
                        @endforelse
                    </div>

                </section>

            </article>
        </main>

        <!-- KOLOM KANAN: SIDEBAR "5 BERITA / PANDUAN TERATAS RELEVAN" -->
        <aside class="lg:col-span-4 space-y-8">
            
            <!-- BLOK 5 PANDUAN TERATAS RELEVAN DENGAN ARTIKEL INI -->
            <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-2xs space-y-5 sticky top-28">
                
                <div class="border-b border-slate-100 pb-3">
                    <div class="flex items-center space-x-2">
                        <span class="w-2 h-2 rounded-xs bg-[#C2410C]"></span>
                        <h3 class="font-display font-extrabold text-sm uppercase tracking-wider text-slate-900">
                            5 Panduan Teratas & Relevan
                        </h3>
                    </div>
                    <p class="text-xs text-slate-500 mt-1">
                        Diprioritaskan dari topik serupa & paling banyak dipelajari
                    </p>
                </div>

                <div class="divide-y divide-slate-100">
                    @foreach($relevantArticles as $index => $relArticle)
                        <article class="py-3.5 first:pt-0 last:pb-0 group">
                            <div class="flex items-start space-x-3.5">
                                <span class="font-display text-xl font-black text-slate-300 group-hover:text-[#C2410C] transition-colors tabular-nums shrink-0 mt-0.5">
                                    {{ sprintf('%02d', $index + 1) }}
                                </span>
                                
                                <div class="space-y-1 min-w-0">
                                    <div class="flex items-center gap-1.5 text-[10px] text-slate-400">
                                        <span class="font-medium text-[#C2410C]">
                                            {{ $relArticle->category->name }}
                                        </span>
                                        <span>•</span>
                                        <span>{{ number_format($relArticle->views_count, 0, ',', '.') }} baca</span>
                                    </div>

                                    <h4 class="font-display font-bold text-xs sm:text-sm text-slate-800 group-hover:text-[#C2410C] transition-colors leading-snug line-clamp-2">
                                        <a href="{{ route('articles.show', $relArticle->slug) }}">
                                            {{ $relArticle->title }}
                                        </a>
                                    </h4>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- Banner Informasi Redaksi Singkat -->
                <div class="p-3.5 bg-slate-50 border border-slate-200/80 rounded-lg text-xs text-slate-600 space-y-1.5">
                    <span class="font-semibold text-slate-800 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-[#0F4C5C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Standar Verifikasi Lapangan
                    </span>
                    <p class="text-[11px] leading-relaxed text-slate-500">
                        Setiap panduan di EdukasiRumahan disusun berdasarkan SNI dan pengalaman mandor/ahli sipil lapangan, bebas dari kompromi kualitas material.
                    </p>
                </div>

            </div>

        </aside>

    </div>

</div>
@endsection

