@extends('layouts.app')

@section('title', 'Pencarian & Direktori Panduan — EdukasiRumahan')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-8 py-8 sm:py-12 space-y-10">

    <!-- HEADER & FORM FILTER PENCARIAN -->
    <header class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-10 shadow-xs space-y-6">
        
        <div class="max-w-3xl space-y-2">
            <span class="text-xs uppercase tracking-widest font-semibold text-[#C2410C]">Direktori & Indeks Panduan</span>
            <h1 class="font-editorial text-2xl sm:text-3xl font-bold text-slate-900">
                Pencarian Solusi Teknis Bangunan
            </h1>
            <p class="text-sm text-slate-600 leading-relaxed">
                Gunakan kata kunci masalah yang Anda hadapi atau filter berdasarkan spesialisasi kategori pengerjaan.
            </p>
        </div>

        <!-- FORM FILTER -->
        <form action="{{ route('search') }}" method="GET" class="space-y-4">
            
            <!-- Bar Pencarian Kata Kunci -->
            <div class="relative flex items-center">
                <svg class="w-5 h-5 text-slate-400 absolute left-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input 
                    type="text" 
                    name="q" 
                    value="{{ $q }}"
                    placeholder="Ketik kata kunci masalah (cth: dinding retak, kemiringan pipa, popping keramik)..." 
                    class="w-full pl-12 pr-28 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-[#C2410C]/20 focus:border-[#C2410C]">
                <button type="submit" class="absolute right-2.5 px-5 py-2 bg-[#C2410C] hover:bg-[#9A3412] text-white text-xs font-semibold rounded-lg shadow-xs transition-colors">
                    Cari Panduan
                </button>
            </div>

            <!-- Opsi Filter Kategori & Sortir -->
            <div class="pt-2 flex flex-col md:flex-row md:items-center justify-between gap-4 text-xs">
                
                <!-- Pills Kategori -->
                <div class="flex items-center flex-wrap gap-2">
                    <span class="font-semibold text-slate-500 mr-1">Kategori:</span>
                    <a href="{{ route('search', array_merge(request()->except(['category', 'page']))) }}" class="px-3 py-1.5 rounded-lg border text-xs font-medium transition-colors {{ empty($selectedCategory) ? 'bg-[#0F4C5C] text-white border-[#0F4C5C]' : 'bg-slate-50 border-slate-200 text-slate-600 hover:bg-slate-100' }}">
                        Semua Kategori
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('search', array_merge(request()->except(['page']), ['category' => $cat->slug])) }}" class="px-3 py-1.5 rounded-lg border text-xs font-medium transition-colors {{ ($selectedCategory && $selectedCategory->id === $cat->id) ? 'bg-[#0F4C5C] text-white border-[#0F4C5C]' : 'bg-slate-50 border-slate-200 text-slate-600 hover:bg-slate-100' }}">
                            {{ $cat->name }} ({{ $cat->articles_count }})
                        </a>
                    @endforeach
                </div>

                <!-- Sortir Dropdown -->
                <div class="flex items-center space-x-2 shrink-0">
                    <span class="font-semibold text-slate-500">Urutkan:</span>
                    <select 
                        name="sort" 
                        onchange="this.form.submit()" 
                        class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium text-slate-700 focus:outline-none focus:ring-1 focus:ring-[#C2410C]">
                        <option value="terbaru" {{ $sort === 'terbaru' ? 'selected' : '' }}>Terbaru Diterbitkan</option>
                        <option value="terpopuler" {{ $sort === 'terpopuler' ? 'selected' : '' }}>Paling Banyak Dibaca</option>
                    </select>
                </div>

            </div>

            <!-- Filter Tag Aktif / Pilihan Tag -->
            <div class="pt-2 flex items-center flex-wrap gap-1.5 text-xs">
                <span class="font-semibold text-slate-400 mr-1">Tag Populer:</span>
                @foreach($tags as $t)
                    <a href="{{ route('search', array_merge(request()->except(['page']), ['tag' => $t->slug])) }}" class="px-2.5 py-1 rounded-md text-[11px] font-medium transition-colors {{ ($selectedTag && $selectedTag->id === $t->id) ? 'bg-orange-100 text-[#C2410C] border border-[#C2410C]' : 'bg-white border border-slate-200 text-slate-600 hover:border-slate-300' }}">
                        #{{ $t->name }}
                    </a>
                @endforeach
                @if($selectedTag || $selectedCategory || !empty($q))
                    <a href="{{ route('search') }}" class="px-2.5 py-1 text-rose-600 hover:underline text-[11px] font-semibold ml-2">
                        Reset Filter &times;
                    </a>
                @endif
            </div>

        </form>

    </header>

    <!-- HASIL PENCARIAN -->
    <section class="space-y-6">
        
        <div class="flex items-center justify-between text-xs text-slate-500 border-b border-slate-200/80 pb-3">
            <span>
                Menampilkan <strong class="text-slate-900">{{ $articles->total() }}</strong> panduan teknis
                @if(!empty($q)) untuk kata kunci "<strong class="text-[#C2410C]">{{ $q }}</strong>" @endif
                @if($selectedCategory) dalam kategori "<strong class="text-slate-900">{{ $selectedCategory->name }}</strong>" @endif
                @if($selectedTag) dengan tag "<strong class="text-slate-900">#{{ $selectedTag->name }}</strong>" @endif
            </span>
            <span>Halaman {{ $articles->currentPage() }} dari {{ $articles->lastPage() }}</span>
        </div>

        @if($articles->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @foreach($articles as $article)
                    <article class="bg-white border border-slate-200/90 rounded-xl overflow-hidden hover:border-slate-300 transition-all flex flex-col justify-between group shadow-2xs">
                        
                        <div class="p-6 space-y-3.5">
                            <div class="flex items-center justify-between text-xs text-slate-500">
                                <a href="{{ route('search', ['category' => $article->category->slug]) }}" class="font-medium text-[#C2410C] uppercase tracking-wider text-[11px] hover:underline">
                                    {{ $article->category->name }}
                                </a>
                                <span class="text-slate-400">
                                    {{ $article->reading_time }} mnt baca
                                </span>
                            </div>

                            <h3 class="font-editorial font-bold text-lg text-slate-900 group-hover:text-[#C2410C] transition-colors leading-snug line-clamp-2">
                                <a href="{{ route('articles.show', $article->slug) }}">
                                    {{ $article->title }}
                                </a>
                            </h3>

                            <p class="text-slate-600 text-sm leading-relaxed font-sans line-clamp-3">
                                {{ $article->excerpt }}
                            </p>
                        </div>

                        <div class="px-6 py-4 bg-slate-50/60 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                            <div class="flex flex-wrap gap-1">
                                @foreach($article->tags->take(2) as $tag)
                                    <span class="px-2 py-0.5 rounded bg-white border border-slate-200 text-[10px] text-slate-600">
                                        #{{ $tag->name }}
                                    </span>
                                @endforeach
                            </div>
                            <span class="text-[11px] text-slate-400">
                                {{ number_format($article->views_count, 0, ',', '.') }} baca
                            </span>
                        </div>

                    </article>
                @endforeach
            </div>

            <!-- Paginasi -->
            <div class="pt-8">
                {{ $articles->links() }}
            </div>
        @else
            <div class="bg-white border border-slate-200 rounded-xl p-12 text-center space-y-4 max-w-xl mx-auto">
                <div class="w-12 h-12 rounded-full bg-orange-50 text-[#C2410C] mx-auto flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="font-display font-bold text-lg text-slate-900">
                    Tidak Ditemukan Panduan yang Sesuai
                </h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    Kami belum menemukan panduan teknis dengan kriteria pencarian tersebut. Silakan periksa kembali ejaan kata kunci atau telusuri berdasarkan kategori utama kami.
                </p>
                <div class="pt-2">
                    <a href="{{ route('search') }}" class="inline-flex items-center px-4 py-2 bg-[#C2410C] text-white text-xs font-semibold rounded-lg hover:bg-[#9A3412] transition-colors">
                        Tampilkan Semua Panduan
                    </a>
                </div>
            </div>
        @endif

    </section>

</div>
@endsection

