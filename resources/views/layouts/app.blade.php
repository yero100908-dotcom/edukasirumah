<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'EdukasiRumahan — Panduan Praktis Konstruksi, Perawatan Hunian & Sanitasi')</title>
    <meta name="description" content="@yield('meta_description', 'Portal edukasi teknis bangunan, instalasi sanitasi, dan perawatan rumah tangga berbahasa Indonesia dengan standar arsitektur dan konstruksi presisi.')">

    <!-- Tipografi Berkarakter: Lora, Plus Jakarta Sans, Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Lora:ital,wght@0,500;0,600;0,700;1,400;1,500&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    <!-- Tailwind CSS (Vite or Fallback CDN) -->
    @if(file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            terracotta: '#C2410C',
                            'terracotta-dark': '#9A3412',
                            forest: '#0F4C5C',
                            alabaster: '#FAF9F6',
                            charcoal: '#1E293B',
                        },
                        fontFamily: {
                            editorial: ['Lora', 'Georgia', 'serif'],
                            display: ['Plus Jakarta Sans', 'sans-serif'],
                            sans: ['Inter', 'sans-serif'],
                        }
                    }
                }
            }
        </script>
        <link rel="stylesheet" href="{{ asset('resources/css/app.css') }}">
    @endif

    <style>
        [x-cloak] { display: none !important; }
        body { background-color: #FAF9F6; color: #1E293B; font-family: 'Inter', sans-serif; }
        .font-editorial { font-family: 'Lora', Georgia, serif; }
        .font-display { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="bg-[#FAF9F6] text-slate-800 antialiased flex flex-col min-h-screen selection:bg-orange-100 selection:text-orange-900" x-data="{ searchModalOpen: false, mobileMenuOpen: false, toastMessage: '', toastVisible: false, showToast(msg) { this.toastMessage = msg; this.toastVisible = true; setTimeout(() => { this.toastVisible = false }, 3500); } }">

    <!-- Quickbar Pintasan Khusus Staf Admin Terautentikasi -->
    @if(Auth::guard('admin')->check())
        <div class="bg-slate-900 text-slate-300 text-xs py-2 px-4 sm:px-8 border-b border-slate-800">
            <div class="max-w-7xl mx-auto flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div class="flex items-center space-x-2">
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span>Sesi Admin Aktif: <strong class="text-white">{{ Auth::guard('admin')->user()->name }}</strong> ({{ strtoupper(Auth::guard('admin')->user()->role) }})</span>
                </div>
                <div class="flex items-center space-x-3 text-[11px]">
                    <a href="{{ route('admin.dashboard') }}" class="text-orange-400 hover:text-orange-300 font-semibold flex items-center gap-1">
                        <span>Panel Dashboard Redaksi</span> &rarr;
                    </a>
                    <span class="text-slate-700">|</span>
                    <a href="{{ route('admin.articles.create') }}" class="px-2.5 py-1 bg-[#C2410C] hover:bg-[#9A3412] text-white rounded font-medium transition-colors">
                        + Tulis Panduan Baru
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Top Bar Edisi & Tanggal -->
    <div class="border-b border-slate-200/80 bg-white/70 text-xs text-slate-500 py-1.5 px-4 sm:px-8">
        <div class="max-w-7xl mx-auto flex justify-between items-center tracking-wide">
            <div class="flex items-center space-x-3">
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-600"></span>
                <span class="font-medium text-slate-700">Jurnal & Panduan Terverifikasi</span>
                <span class="text-slate-300">|</span>
                <span class="hidden sm:inline">Edisi Arsitektur & Perawatan Hunian Indonesia</span>
            </div>
            <div class="flex items-center space-x-4 text-[11px] text-slate-500">
                <span>{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                <span class="text-slate-300">|</span>
                <span class="text-slate-600 font-medium">Bebas Iklan & Akses Terbuka</span>
            </div>
        </div>
    </div>

    <!-- Header Minimalis & Berwibawa -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200 transition-all shadow-[0_1px_3px_rgba(0,0,0,0.02)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-8 h-20 flex items-center justify-between">
            
            <!-- Logo Kiri -->
            <div class="flex items-center space-x-3">
                <a href="{{ route('home') }}" class="group flex flex-col">
                    <span class="font-display font-extrabold text-xl sm:text-2xl tracking-tight text-slate-900 group-hover:text-[#C2410C] transition-colors">
                        EDUKASI<span class="text-[#C2410C]">RUMAHAN</span>
                    </span>
                    <span class="text-[10px] sm:text-[11px] font-medium uppercase tracking-[0.22em] text-slate-500 -mt-1">
                        Pusat Panduan Teknis & Struktur
                    </span>
                </a>
            </div>

            <!-- Navigasi Kategori Tengah -->
            <nav class="hidden lg:flex items-center space-x-1 xl:space-x-2 text-[13px] font-medium text-slate-600">
                <a href="{{ route('home') }}" class="px-3 py-2 rounded-md hover:text-[#C2410C] hover:bg-slate-50 transition-colors {{ request()->routeIs('home') ? 'text-[#C2410C] font-semibold bg-orange-50/50' : '' }}">
                    Beranda
                </a>
                <a href="{{ route('search', ['category' => 'konstruksi-pondasi']) }}" class="px-3 py-2 rounded-md hover:text-[#C2410C] hover:bg-slate-50 transition-colors {{ request('category') === 'konstruksi-pondasi' ? 'text-[#C2410C] font-semibold bg-orange-50/50' : '' }}">
                    Konstruksi & Pondasi
                </a>
                <a href="{{ route('search', ['category' => 'keramik-lantai']) }}" class="px-3 py-2 rounded-md hover:text-[#C2410C] hover:bg-slate-50 transition-colors {{ request('category') === 'keramik-lantai' ? 'text-[#C2410C] font-semibold bg-orange-50/50' : '' }}">
                    Keramik & Lantai
                </a>
                <a href="{{ route('search', ['category' => 'instalasi-pipa-sanitasi']) }}" class="px-3 py-2 rounded-md hover:text-[#C2410C] hover:bg-slate-50 transition-colors {{ request('category') === 'instalasi-pipa-sanitasi' ? 'text-[#C2410C] font-semibold bg-orange-50/50' : '' }}">
                    Instalasi Pipa
                </a>
                <a href="{{ route('search', ['category' => 'perawatan-rumah-tangga']) }}" class="px-3 py-2 rounded-md hover:text-[#C2410C] hover:bg-slate-50 transition-colors {{ request('category') === 'perawatan-rumah-tangga' ? 'text-[#C2410C] font-semibold bg-orange-50/50' : '' }}">
                    Perawatan Hunian
                </a>
            </nav>

            <!-- Kanan: Tombol Pencarian Cepat -->
            <div class="flex items-center space-x-3">
                <button 
                    @click="searchModalOpen = true" 
                    type="button" 
                    class="flex items-center space-x-2 px-3.5 py-2 bg-slate-50 hover:bg-slate-100 border border-slate-200/90 rounded-lg text-slate-500 hover:text-slate-800 text-xs transition-all shadow-xs"
                    title="Cari panduan teknis...">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <span class="hidden sm:inline font-normal">Cari panduan...</span>
                    <kbd class="hidden md:inline-block px-1.5 py-0.5 text-[10px] font-semibold text-slate-500 bg-white border border-slate-300 rounded shadow-2xs">Ctrl K</kbd>
                </button>

                <!-- Tombol Mobile Menu -->
                <button 
                    @click="mobileMenuOpen = !mobileMenuOpen" 
                    type="button" 
                    class="lg:hidden p-2 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="mobileMenuOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

        </div>

        <!-- Mobile Navigation Menu Dropdown -->
        <div x-show="mobileMenuOpen" x-cloak class="lg:hidden border-t border-slate-200 bg-white px-4 py-4 space-y-3 text-sm shadow-lg">
            <!-- Form Pencarian Cepat Mobile -->
            <form action="{{ route('search') }}" method="GET" class="relative">
                <input 
                    type="text" 
                    name="q" 
                    placeholder="Cari masalah bangunan..." 
                    class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-[#C2410C]">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </form>

            <div class="space-y-1 pt-1 font-medium text-slate-700">
                <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md hover:bg-slate-50 {{ request()->routeIs('home') ? 'text-[#C2410C] font-semibold bg-orange-50/50' : '' }}">Beranda</a>
                <a href="{{ route('search', ['category' => 'konstruksi-pondasi']) }}" class="block px-3 py-2 rounded-md hover:bg-slate-50 {{ request('category') === 'konstruksi-pondasi' ? 'text-[#C2410C] font-semibold bg-orange-50/50' : '' }}">Konstruksi & Pondasi</a>
                <a href="{{ route('search', ['category' => 'keramik-lantai']) }}" class="block px-3 py-2 rounded-md hover:bg-slate-50 {{ request('category') === 'keramik-lantai' ? 'text-[#C2410C] font-semibold bg-orange-50/50' : '' }}">Keramik & Lantai</a>
                <a href="{{ route('search', ['category' => 'instalasi-pipa-sanitasi']) }}" class="block px-3 py-2 rounded-md hover:bg-slate-50 {{ request('category') === 'instalasi-pipa-sanitasi' ? 'text-[#C2410C] font-semibold bg-orange-50/50' : '' }}">Instalasi Pipa & Sanitasi</a>
                <a href="{{ route('search', ['category' => 'perawatan-rumah-tangga']) }}" class="block px-3 py-2 rounded-md hover:bg-slate-50 {{ request('category') === 'perawatan-rumah-tangga' ? 'text-[#C2410C] font-semibold bg-orange-50/50' : '' }}">Perawatan Rumah Tangga</a>
                <a href="{{ route('search') }}" class="block px-3 py-2 rounded-md font-semibold text-[#C2410C] bg-orange-50/70 border border-orange-200/50 mt-2">Jelajahi Semua Arsip Panduan</a>
            </div>
        </div>
    </header>

    <!-- Konten Utama Halaman -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer Editorial & Berwibawa -->
    <footer class="bg-white border-t border-slate-200 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                
                <!-- Kolom Brand & Nilai -->
                <div class="md:col-span-2 space-y-4">
                    <span class="font-display font-extrabold text-2xl tracking-tight text-slate-900">
                        EDUKASI<span class="text-[#C2410C]">RUMAHAN</span>
                    </span>
                    <p class="text-sm text-slate-600 leading-relaxed max-w-md">
                        Pusat referensi dan jurnal teknik konstruksi sipil, metode pemasangan finishing, sistem utilitas pipa sanitasi, serta perawatan rumah tangga mandiri dengan standar keamanan dan ketahanan jangka panjang.
                    </p>
                    <div class="flex items-center space-x-2 text-xs text-slate-500 pt-2">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Materi disusun & divalidasi oleh praktisi teknik sipil & arsitektur bangunan.</span>
                    </div>
                </div>

                <!-- Direktori Kategori -->
                <div>
                    <h4 class="font-display font-bold text-xs uppercase tracking-wider text-slate-900 mb-4">
                        Kategori Pembahasan
                    </h4>
                    <ul class="space-y-2.5 text-sm text-slate-600">
                        <li>
                            <a href="{{ route('search', ['category' => 'konstruksi-pondasi']) }}" class="hover:text-[#C2410C] transition-colors">
                                Konstruksi & Pondasi
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('search', ['category' => 'keramik-lantai']) }}" class="hover:text-[#C2410C] transition-colors">
                                Keramik & Lantai
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('search', ['category' => 'instalasi-pipa-sanitasi']) }}" class="hover:text-[#C2410C] transition-colors">
                                Instalasi Pipa & Sanitasi
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('search', ['category' => 'perawatan-rumah-tangga']) }}" class="hover:text-[#C2410C] transition-colors">
                                Perawatan Rumah Tangga
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Prinsip & Navigasi Cepat -->
                <div>
                    <h4 class="font-display font-bold text-xs uppercase tracking-wider text-slate-900 mb-4">
                        Indeks Panduan
                    </h4>
                    <ul class="space-y-2.5 text-sm text-slate-600">
                        <li><a href="{{ route('search', ['sort' => 'terpopuler']) }}" class="hover:text-[#C2410C] transition-colors">Panduan Terpopuler</a></li>
                        <li><a href="{{ route('search') }}" class="hover:text-[#C2410C] transition-colors">Pencarian Lengkap</a></li>
                        <li><a href="{{ route('search', ['tag' => 'anti-mampet']) }}" class="hover:text-[#C2410C] transition-colors">Topik Anti Mampet</a></li>
                        <li><a href="{{ route('search', ['tag' => 'pondasi-kokoh']) }}" class="hover:text-[#C2410C] transition-colors">Topik Pondasi Kokoh</a></li>
                    </ul>
                </div>

            </div>

            <!-- Bar Bawah Legal & Akses Redaksi -->
            <div class="border-t border-slate-100 mt-12 pt-8 flex flex-col sm:flex-row justify-between items-center text-xs text-slate-500 gap-4">
                <p>&copy; {{ date('Y') }} EdukasiRumahan. Seluruh konten edukasi disajikan untuk keselamatan dan ketahanan hunian Indonesia.</p>
                <div class="flex items-center space-x-4">
                    <span class="text-slate-400">Terbuka untuk Publik • Tanpa Login</span>
                    <span class="text-slate-300">•</span>
                    <a href="{{ route('admin.login') }}" class="text-slate-400 hover:text-slate-700 transition-colors" title="Akses Tim Redaksi">
                        Panel Redaksi
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Search Modal (Alpine.js) -->
    <div 
        x-show="searchModalOpen" 
        x-cloak 
        @keydown.window.escape="searchModalOpen = false"
        @keydown.window.ctrl.k.prevent="searchModalOpen = true"
        @keydown.window.cmd.k.prevent="searchModalOpen = true"
        class="fixed inset-0 z-50 overflow-y-auto"
        role="dialog" 
        aria-modal="true">
        
        <div class="min-h-screen px-4 text-center flex items-start justify-center pt-20 sm:pt-28">
            <!-- Backdrop -->
            <div 
                x-show="searchModalOpen" 
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="searchModalOpen = false"
                class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity"></div>

            <!-- Dialog Modal Box -->
            <div 
                x-show="searchModalOpen"
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative bg-white rounded-xl max-w-2xl w-full text-left overflow-hidden shadow-2xl border border-slate-200 z-10">
                
                <form action="{{ route('search') }}" method="GET" class="p-4 sm:p-6 border-b border-slate-100">
                    <div class="relative flex items-center">
                        <svg class="w-5 h-5 text-slate-400 absolute left-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input 
                            type="text" 
                            name="q" 
                            placeholder="Cari topik masalah (cth: pipa mampet, pondasi batu kali, popping keramik)..." 
                            class="w-full pl-11 pr-12 py-3 bg-slate-50 border border-slate-200 rounded-lg text-slate-800 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-[#C2410C]/20 focus:border-[#C2410C]"
                            autofocus>
                        <button type="submit" class="absolute right-2 px-3 py-1.5 bg-[#C2410C] text-white text-xs font-semibold rounded-md hover:bg-[#9A3412] transition-colors">
                            Cari
                        </button>
                    </div>
                </form>

                <!-- Saran Topik Cepat -->
                <div class="p-4 sm:p-6 bg-slate-50/70 text-xs text-slate-600">
                    <span class="font-semibold text-slate-700 block mb-2">Topik Masalah Sering Dicari:</span>
                    <div class="flex flex-wrap gap-1.5">
                        <a href="{{ route('search', ['tag' => 'anti-mampet']) }}" class="px-2.5 py-1 bg-white border border-slate-200 rounded-md hover:border-[#C2410C] hover:text-[#C2410C] transition-colors">
                            Anti Mampet
                        </a>
                        <a href="{{ route('search', ['tag' => 'keramik-kopong']) }}" class="px-2.5 py-1 bg-white border border-slate-200 rounded-md hover:border-[#C2410C] hover:text-[#C2410C] transition-colors">
                            Keramik Kopong
                        </a>
                        <a href="{{ route('search', ['tag' => 'pondasi-kokoh']) }}" class="px-2.5 py-1 bg-white border border-slate-200 rounded-md hover:border-[#C2410C] hover:text-[#C2410C] transition-colors">
                            Pondasi Kokoh
                        </a>
                        <a href="{{ route('search', ['tag' => 'grease-trap']) }}" class="px-2.5 py-1 bg-white border border-slate-200 rounded-md hover:border-[#C2410C] hover:text-[#C2410C] transition-colors">
                            Grease Trap
                        </a>
                        <a href="{{ route('search', ['tag' => 'kemiringan-pipa']) }}" class="px-2.5 py-1 bg-white border border-slate-200 rounded-md hover:border-[#C2410C] hover:text-[#C2410C] transition-colors">
                            Kemiringan Pipa
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Toast Notification (Alpine.js) -->
    <div 
        x-show="toastVisible" 
        x-cloak
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-4"
        class="fixed bottom-6 right-6 z-50 bg-slate-900 text-white px-5 py-3 rounded-lg shadow-xl text-sm flex items-center space-x-3 border border-slate-800">
        <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <span x-text="toastMessage"></span>
    </div>

    @stack('scripts')
</body>
</html>

