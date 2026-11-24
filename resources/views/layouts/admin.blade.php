<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel Redaksi — EdukasiRumahan')</title>

    <!-- Tipografi -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700&family=Lora:ital,wght@0,500;0,600;1,400&display=swap" rel="stylesheet">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    <!-- Tailwind CSS -->
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
        body { background-color: #F8FAFC; color: #1E293B; font-family: 'Inter', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex" x-data="{ sidebarOpen: false }">

    <!-- Mobile Backdrop -->
    <div 
        x-show="sidebarOpen" 
        x-cloak 
        @click="sidebarOpen = false" 
        class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-xs lg:hidden"></div>

    <!-- Sidebar Admin -->
    <aside 
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-300 flex flex-col transition-transform duration-200 ease-in-out lg:static lg:translate-x-0 border-r border-slate-800">
        
        <!-- Sidebar Brand -->
        <div class="h-20 flex items-center px-6 border-b border-slate-800 bg-slate-950/40">
            <a href="{{ route('admin.dashboard') }}" class="flex flex-col">
                <span class="font-display font-extrabold text-lg tracking-tight text-white">
                    EDUKASI<span class="text-orange-500">RUMAHAN</span>
                </span>
                <span class="text-[10px] uppercase tracking-widest text-slate-400">
                    Panel Redaksi & CMS
                </span>
            </a>
        </div>

        <!-- Sidebar Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto text-sm">
            
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-orange-600 text-white font-medium' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
                <span>Ringkasan / Statistik</span>
            </a>

            <div class="pt-4 pb-2 text-[11px] font-semibold uppercase tracking-wider text-slate-400 px-3">
                Manajemen Konten
            </div>

            <a href="{{ route('admin.articles.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.articles.*') ? 'bg-orange-600 text-white font-medium' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Artikel & Panduan</span>
            </a>

            <a href="{{ route('admin.categories.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.categories.*') ? 'bg-orange-600 text-white font-medium' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                <span>Kategori Topik</span>
            </a>

            <a href="{{ route('admin.tags.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.tags.*') ? 'bg-orange-600 text-white font-medium' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                </svg>
                <span>Tag Topik</span>
            </a>

            <a href="{{ route('admin.users.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-orange-600 text-white font-medium' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span>Pengguna & Role</span>
            </a>

            <div class="pt-4 pb-2 text-[11px] font-semibold uppercase tracking-wider text-slate-400 px-3">
                Interaksi & Moderasi
            </div>

            @php
                $pendingBadge = \App\Models\Comment::where('is_approved', false)->count();
            @endphp
            <a href="{{ route('admin.comments.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.comments.*') ? 'bg-orange-600 text-white font-medium' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <span>Moderasi Diskusi</span>
                </div>
                @if($pendingBadge > 0)
                    <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-amber-500 text-slate-900">
                        {{ $pendingBadge }}
                    </span>
                @endif
            </a>

            <div class="pt-6 border-t border-slate-800/80 my-4">
                <a href="{{ route('home') }}" target="_blank" class="flex items-center space-x-3 px-3 py-2 text-xs text-slate-400 hover:text-white transition-colors">
                    <svg class="w-4 h-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    <span>Buka Portal Publik</span>
                </a>
            </div>

        </nav>

        <!-- Profil Admin & Logout -->
        <div class="p-4 border-t border-slate-800 bg-slate-950/60 flex items-center justify-between">
            <div class="flex flex-col truncate pr-2">
                <span class="text-sm font-semibold text-white truncate">
                    {{ Auth::guard('admin')->user()->name ?? 'Administrator' }}
                </span>
                <span class="text-[11px] text-orange-400 uppercase font-medium">
                    {{ Auth::guard('admin')->user()->role ?? 'superadmin' }}
                </span>
            </div>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="p-2 text-slate-400 hover:text-white hover:bg-slate-800 rounded-lg transition-colors" title="Keluar dari sesi admin">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>
        </div>

    </aside>

    <!-- Konten Utama Panel -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- Header Topbar -->
        <header class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-6 lg:px-8">
            <div class="flex items-center space-x-4">
                <button 
                    @click="sidebarOpen = true" 
                    type="button" 
                    class="lg:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <h1 class="text-lg lg:text-xl font-bold font-display text-slate-900">
                    @yield('page_title', 'Dashboard Redaksi')
                </h1>
            </div>

            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.articles.create') }}" class="inline-flex items-center space-x-2 px-3.5 py-2 bg-[#C2410C] hover:bg-[#9A3412] text-white text-xs font-semibold rounded-lg shadow-xs transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Tulis Panduan Baru</span>
                </a>
            </div>
        </header>

        <!-- Flash Messages -->
        <div class="px-6 lg:px-8 pt-6">
            @if(session('success'))
                <div class="p-4 mb-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    @if(session('live_article_url'))
                        <a href="{{ session('live_article_url') }}" target="_blank" class="inline-flex items-center space-x-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded text-xs font-semibold shrink-0 transition-colors">
                            <span>Lihat Panduan di Web Publik</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </a>
                    @endif
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 mb-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif
        </div>

        <!-- Halaman Konten Admin -->
        <main class="flex-1 overflow-y-auto px-6 lg:px-8 pb-12">
            @yield('content')
        </main>

    </div>

    @stack('scripts')
</body>
</html>

