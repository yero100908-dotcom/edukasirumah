<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Redaksi — EdukasiRumahan CMS</title>
    
    <!-- Tipografi -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&family=Lora:ital,wght@0,500;0,600&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
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
</head>
<body class="bg-[#FAF9F6] text-slate-800 antialiased min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-white border border-slate-200 rounded-2xl p-8 sm:p-10 shadow-xs space-y-6">
        
        <!-- Header Identitas Redaksi -->
        <div class="text-center space-y-2">
            <span class="font-display font-extrabold text-2xl tracking-tight text-slate-900 block">
                EDUKASI<span class="text-[#C2410C]">RUMAHAN</span>
            </span>
            <span class="inline-block text-[11px] font-semibold uppercase tracking-widest text-slate-400 bg-slate-100 px-2.5 py-0.5 rounded">
                Panel Autentikasi Redaksi
            </span>
            <p class="text-xs text-slate-500 pt-1">
                Akses terisolasi untuk tim editor dan kurator materi teknis bangunan.
            </p>
        </div>

        <!-- Flash Notifikasi -->
        @if(session('info'))
            <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg text-xs text-blue-700">
                {{ session('info') }}
            </div>
        @endif

        @if($errors->any())
            <div class="p-3 bg-rose-50 border border-rose-200 rounded-lg text-xs text-rose-700 space-y-1">
                @foreach($errors->all() as $err)
                    <div>• {{ $err }}</div>
                @endforeach
            </div>
        @endif

        <!-- Form Login -->
        <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">
                    Alamat Email Redaksi
                </label>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    value="{{ old('email', 'admin@edukasirumahan.id') }}" 
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#C2410C]/20 focus:border-[#C2410C]"
                    placeholder="nama@edukasirumahan.id"
                    required 
                    autofocus>
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">
                    Kata Sandi
                </label>
                <input 
                    type="password" 
                    name="password" 
                    id="password" 
                    value="admin123"
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#C2410C]/20 focus:border-[#C2410C]"
                    placeholder="••••••••"
                    required>
            </div>

            <div class="flex items-center justify-between text-xs text-slate-600 pt-1">
                <label class="flex items-center space-x-2 cursor-pointer select-none">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-[#C2410C] focus:ring-[#C2410C]">
                    <span>Ingat sesi masuk</span>
                </label>
                <span class="text-slate-400 text-[11px]">Enkripsi SSL SHA-256</span>
            </div>

            <button type="submit" class="w-full py-2.5 px-4 bg-[#C2410C] hover:bg-[#9A3412] text-white text-xs font-bold uppercase tracking-wider rounded-lg shadow-xs transition-colors mt-2">
                Masuk ke Panel Redaksi
            </button>
        </form>

        <!-- Keterangan Keamanan & Akun Awal -->
        <div class="pt-4 border-t border-slate-100 text-[11px] text-slate-500 space-y-2 bg-slate-50/70 -mx-8 -mb-8 p-6 rounded-b-2xl">
            <span class="font-semibold text-slate-700 block">Kredensial Pengujian (Seeder Otomatis):</span>
            <div class="font-mono text-[11px] text-slate-600 bg-white p-2.5 rounded border border-slate-200 space-y-0.5">
                <div>Email: <strong class="text-slate-800">admin@edukasirumahan.id</strong></div>
                <div>Sandi: <strong class="text-slate-800">admin123</strong></div>
            </div>
            <div class="text-center pt-2">
                <a href="{{ route('home') }}" class="text-slate-500 hover:text-slate-800 transition-colors">
                    &larr; Kembali ke Beranda Publik
                </a>
            </div>
        </div>

    </div>

</body>
</html>

