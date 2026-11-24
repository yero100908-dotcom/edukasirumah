@extends('layouts.admin')

@section('title', 'Edit Panduan: ' . $article->title . ' — EdukasiRumahan')
@section('page_title', 'Perbarui Panduan Teknis')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <a href="{{ route('admin.articles.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Kembali ke Daftar Panduan</span>
        </a>

        @if($article->status === 'published')
            <a href="{{ route('articles.show', $article->slug) }}" target="_blank" class="text-xs font-semibold text-[#C2410C] hover:underline flex items-center gap-1">
                <span>Lihat Tampilan Publik</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
            </a>
        @endif
    </div>

    @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-700 space-y-1">
            <strong class="font-bold block mb-1">Terdapat kesalahan pada isian formulir:</strong>
            @foreach($errors->all() as $error)
                <div>• {{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form 
        action="{{ route('admin.articles.update', $article->id) }}" 
        method="POST" 
        enctype="multipart/form-data" 
        class="space-y-8"
        x-data="{
            title: @js(old('title', $article->title)),
            status: @js(old('status', $article->status)),
            slugPreview() {
                return this.title.toLowerCase()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/[\s_-]+/g, '-')
                    .replace(/^-+|-+$/g, '');
            },
            insertTag(openTag, closeTag = '') {
                const textarea = document.getElementById('content-editor');
                const start = textarea.selectionStart;
                const end = textarea.selectionEnd;
                const text = textarea.value;
                const selected = text.substring(start, end) || 'Teks pilihan';
                textarea.value = text.substring(0, start) + openTag + selected + closeTag + text.substring(end);
                textarea.focus();
                textarea.setSelectionRange(start + openTag.length, start + openTag.length + selected.length);
            },
            insertComponent(type) {
                const textarea = document.getElementById('content-editor');
                let snippet = '';
                if (type === 'tools') {
                    snippet = '\n<div class=\"edu-box-tools\">\n    <h3 class=\"font-semibold text-slate-800 text-base mb-2\">Alat & Bahan Standar</h3>\n    <ul class=\"list-disc list-inside space-y-1 text-slate-700 text-sm\">\n        <li>Item alat/bahan 1</li>\n        <li>Item alat/bahan 2</li>\n    </ul>\n</div>\n';
                } else if (type === 'steps') {
                    snippet = '\n<div class=\"edu-box-steps\">\n    <ol class=\"list-decimal list-inside space-y-2 text-slate-700 text-sm\">\n        <li><strong>Tahap 1:</strong> Deskripsi langkah teknis pengerjaan...</li>\n        <li><strong>Tahap 2:</strong> Deskripsi langkah teknis pengerjaan...</li>\n    </ol>\n</div>\n';
                } else if (type === 'warning') {
                    snippet = '\n<div class=\"edu-box-warning\">\n    <h3 class=\"font-semibold text-amber-900 text-base mb-1\">Peringatan Teknis & Kesalahan Fatal</h3>\n    <p class=\"text-sm text-slate-700\">Hindari kesalahan berikut karena berisiko merusak struktur...</p>\n</div>\n';
                }
                const start = textarea.selectionStart;
                const end = textarea.selectionEnd;
                textarea.value = textarea.value.substring(0, start) + snippet + textarea.value.substring(end);
                textarea.focus();
            },
            uploadImage(event) {
                const file = event.target.files[0];
                if (!file) return;
                const formData = new FormData();
                formData.append('image', file);
                formData.append('_token', '{{ csrf_token() }}');
                fetch('{{ route('admin.upload.image') }}', {
                    method: 'POST',
                    body: formData
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        const imgTag = `\n<img src=\"${data.url}\" alt=\"Ilustrasi Teknis\" class=\"rounded-lg border border-slate-200 my-4 max-w-full\" />\n`;
                        const textarea = document.getElementById('content-editor');
                        textarea.value += imgTag;
                    } else {
                        alert('Gagal mengunggah gambar: ' + (data.message || 'Error'));
                    }
                })
                .catch(e => alert('Terjadi kesalahan jaringan saat upload gambar.'));
            }
        }">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- KOLOM KIRI: EDITOR KONTEN UTAMA (8/12) -->
            <div class="lg:col-span-8 bg-white border border-slate-200 rounded-xl p-6 sm:p-8 shadow-2xs space-y-6">
                
                <!-- Judul Artikel -->
                <div>
                    <label for="title" class="block text-xs font-bold uppercase tracking-wider text-slate-800 mb-1.5">
                        Judul Panduan Teknis <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="title" 
                        id="title" 
                        x-model="title"
                        value="{{ old('title', $article->title) }}" 
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg text-base font-medium text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#C2410C]/20 focus:border-[#C2410C]"
                        required>
                    <div class="text-[11px] text-slate-400 mt-1 flex items-center gap-1 font-mono">
                        <span>Slug URL saat ini:</span>
                        <span class="text-slate-600 font-semibold">{{ $article->slug }}</span>
                    </div>
                </div>

                <!-- Excerpt (Ringkasan 2 Kalimat) -->
                <div>
                    <label for="excerpt" class="block text-xs font-bold uppercase tracking-wider text-slate-800 mb-1.5">
                        Ringkasan Masalah & Solusi (Excerpt) <span class="text-rose-500">*</span>
                    </label>
                    <textarea 
                        name="excerpt" 
                        id="excerpt" 
                        rows="2" 
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#C2410C]/20 focus:border-[#C2410C]"
                        required>{{ old('excerpt', $article->excerpt) }}</textarea>
                </div>

                <!-- Rich Text Editor Toolbar -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-800 mb-1.5">
                        Isi Konten Panduan Teknis <span class="text-rose-500">*</span>
                    </label>

                    <!-- Editor Toolbar -->
                    <div class="border border-slate-200 rounded-t-lg bg-slate-100/80 p-2 flex flex-wrap items-center gap-1.5 text-xs text-slate-700">
                        <button type="button" @click="insertTag('<h2>', '</h2>')" class="px-2.5 py-1 bg-white hover:bg-slate-50 border border-slate-200 rounded font-bold" title="Heading 2">
                            H2
                        </button>
                        <button type="button" @click="insertTag('<h3>', '</h3>')" class="px-2.5 py-1 bg-white hover:bg-slate-50 border border-slate-200 rounded font-bold" title="Heading 3">
                            H3
                        </button>
                        <span class="text-slate-300">|</span>
                        <button type="button" @click="insertTag('<strong>', '</strong>')" class="px-2.5 py-1 bg-white hover:bg-slate-50 border border-slate-200 rounded font-bold" title="Tebal (Bold)">
                            B
                        </button>
                        <button type="button" @click="insertTag('<em>', '</em>')" class="px-2.5 py-1 bg-white hover:bg-slate-50 border border-slate-200 rounded italic" title="Miring (Italic)">
                            I
                        </button>
                        <span class="text-slate-300">|</span>
                        <button type="button" @click="insertTag('<ul>\n    <li>', '</li>\n</ul>')" class="px-2.5 py-1 bg-white hover:bg-slate-50 border border-slate-200 rounded" title="Daftar Poin">
                            • Bullet
                        </button>
                        <button type="button" @click="insertTag('<ol>\n    <li>', '</li>\n</ol>')" class="px-2.5 py-1 bg-white hover:bg-slate-50 border border-slate-200 rounded" title="Daftar Berurutan">
                            1. List
                        </button>
                        <span class="text-slate-300">|</span>
                        
                        <!-- Tombol Komponen Edukasi -->
                        <button type="button" @click="insertComponent('tools')" class="px-2.5 py-1 bg-slate-800 text-white hover:bg-slate-900 rounded font-medium text-[11px]" title="Sisipkan Kotak Alat & Bahan">
                            + Alat & Bahan
                        </button>
                        <button type="button" @click="insertComponent('steps')" class="px-2.5 py-1 bg-[#0F4C5C] text-white hover:bg-[#0c3c49] rounded font-medium text-[11px]" title="Sisipkan Kotak Langkah Kerja">
                            + Langkah Kerja
                        </button>
                        <button type="button" @click="insertComponent('warning')" class="px-2.5 py-1 bg-[#C2410C] text-white hover:bg-[#9A3412] rounded font-medium text-[11px]" title="Sisipkan Kotak Peringatan Teknis">
                            + Peringatan Teknis
                        </button>

                        <span class="text-slate-300">|</span>
                        <!-- Sisipkan Gambar Editor -->
                        <label class="px-2.5 py-1 bg-white hover:bg-slate-50 border border-slate-200 rounded cursor-pointer font-medium text-[11px] inline-flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>Unggah Gambar</span>
                            <input type="file" accept="image/*" class="hidden" @change="uploadImage($event)">
                        </label>
                    </div>

                    <!-- Textarea Konten -->
                    <textarea 
                        name="content" 
                        id="content-editor" 
                        rows="18" 
                        class="w-full p-4 bg-slate-50 border-x border-b border-slate-200 rounded-b-lg font-mono text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#C2410C]/20 focus:border-[#C2410C]"
                        required>{{ old('content', $article->content) }}</textarea>
                </div>

            </div>

            <!-- KOLOM KANAN: PENGATURAN METADATA & PUBLIKASI (4/12) -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- KARTU PUBLIKASI -->
                <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-2xs space-y-4">
                    <h3 class="font-display font-bold text-sm text-slate-900 uppercase tracking-wider pb-2 border-b border-slate-100">
                        Status Publikasi
                    </h3>

                    <div>
                        <label for="status" class="block text-xs font-semibold text-slate-700 mb-1">
                            Pilih Status
                        </label>
                        <select 
                            name="status" 
                            id="status" 
                            x-model="status"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium text-slate-800 focus:outline-none focus:ring-1 focus:ring-[#C2410C]">
                            <option value="published">Tayang Sekarang (Published)</option>
                            <option value="scheduled">Jadwalkan Tayang (Scheduled)</option>
                            <option value="draft">Simpan Draf (Draft)</option>
                        </select>
                    </div>

                    <div x-show="status === 'scheduled'" x-cloak class="space-y-1">
                        <label for="published_at" class="block text-xs font-semibold text-slate-700">
                            Waktu Tayang Terjadwal
                        </label>
                        <input 
                            type="datetime-local" 
                            name="published_at" 
                            id="published_at" 
                            value="{{ old('published_at', $article->published_at ? $article->published_at->format('Y-m-d\TH:i') : now()->addDay()->format('Y-m-d\TH:i')) }}"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800">
                    </div>

                    <div class="pt-2 border-t border-slate-100 text-xs text-slate-500 space-y-1">
                        <div>Pembaca: <strong class="text-slate-800">{{ number_format($article->views_count, 0, ',', '.') }}</strong> kali</div>
                        <div>Dibuat: <strong class="text-slate-800">{{ $article->created_at->format('d/m/Y H:i') }}</strong></div>
                    </div>

                    <button type="submit" class="w-full py-2.5 px-4 bg-[#C2410C] hover:bg-[#9A3412] text-white text-xs font-bold uppercase tracking-wider rounded-lg shadow-xs transition-colors">
                        Simpan Perubahan
                    </button>
                </div>

                <!-- KARTU KATEGORI & TAGS -->
                <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-2xs space-y-4">
                    <h3 class="font-display font-bold text-sm text-slate-900 uppercase tracking-wider pb-2 border-b border-slate-100">
                        Kategori & Tag
                    </h3>

                    <div>
                        <label for="category_id" class="block text-xs font-semibold text-slate-700 mb-1">
                            Kategori Utama <span class="text-rose-500">*</span>
                        </label>
                        <select 
                            name="category_id" 
                            id="category_id" 
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium text-slate-800 focus:outline-none focus:ring-1 focus:ring-[#C2410C]"
                            required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $article->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tag Checkboxes -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-2">
                            Pilih Tag yang Relevan
                        </label>
                        <div class="max-h-36 overflow-y-auto space-y-1.5 p-2 bg-slate-50 border border-slate-200 rounded-lg text-xs">
                            @foreach($tags as $t)
                                <label class="flex items-center space-x-2 cursor-pointer select-none">
                                    <input 
                                        type="checkbox" 
                                        name="tags[]" 
                                        value="{{ $t->id }}" 
                                        {{ in_array($t->id, old('tags', $selectedTagIds)) ? 'checked' : '' }}
                                        class="rounded border-slate-300 text-[#C2410C] focus:ring-[#C2410C]">
                                    <span>#{{ $t->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Input Tag Baru -->
                    <div>
                        <label for="new_tags" class="block text-xs font-semibold text-slate-700 mb-1">
                            Tambah Tag Baru (Pisahkan Koma)
                        </label>
                        <input 
                            type="text" 
                            name="new_tags" 
                            id="new_tags" 
                            value="{{ old('new_tags') }}"
                            placeholder="Contoh: Sloof Gantung, Waterproofing" 
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 placeholder-slate-400">
                    </div>

                </div>

                <!-- KARTU THUMBNAIL -->
                <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-2xs space-y-4" x-data="{ photoPreview: null }">
                    <h3 class="font-display font-bold text-sm text-slate-900 uppercase tracking-wider pb-2 border-b border-slate-100">
                        Gambar Sampul (Thumbnail)
                    </h3>

                    @if($article->thumbnail_url)
                        <div>
                            <span class="text-[11px] text-slate-400 block mb-1">Thumbnail Saat Ini:</span>
                            <img src="{{ $article->thumbnail_url }}" class="w-full h-36 object-cover rounded-lg border border-slate-200">
                        </div>
                    @endif

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Ganti Thumbnail</label>
                        <input 
                            type="file" 
                            name="thumbnail" 
                            id="thumbnail" 
                            accept="image/*"
                            @change="const file = $event.target.files[0]; if(file) { const reader = new FileReader(); reader.onload = (e) => { photoPreview = e.target.result; }; reader.readAsDataURL(file); }"
                            class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                    </div>

                    <div x-show="photoPreview" x-cloak class="mt-2">
                        <span class="text-xs font-semibold text-slate-600 block mb-1">Pratinjau Pengganti:</span>
                        <img :src="photoPreview" class="w-full h-36 object-cover rounded-lg border border-slate-200">
                    </div>
                </div>

            </div>

        </div>

    </form>

</div>
@endsection

