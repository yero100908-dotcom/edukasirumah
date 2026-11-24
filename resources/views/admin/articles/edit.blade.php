@extends('layouts.admin')

@section('title', 'Perbarui Panduan: ' . $article->title . ' — EdukasiRumahan')
@section('page_title', 'Perbarui Panduan Teknis')

@section('content')
<div class="max-w-6xl mx-auto space-y-6" x-data="articleEditor()">

    <!-- Header Atas: Navigasi Balik & Aksi Cepat -->
    <div class="flex items-center justify-between pb-3 border-b border-slate-200">
        <a href="{{ route('admin.articles.index') }}" class="text-xs font-semibold text-slate-600 hover:text-[#C2410C] flex items-center gap-1.5 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Kembali ke Daftar Panduan</span>
        </a>

        @if($article->status === 'published')
            <a href="{{ route('articles.show', $article->slug) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#C2410C] hover:text-[#9A3412] transition-colors bg-orange-50 hover:bg-orange-100 px-3 py-1.5 rounded-lg border border-orange-200 shadow-2xs">
                <span>Buka di Web Publik</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
            </a>
        @endif
    </div>

    @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-800 space-y-1 shadow-2xs">
            <strong class="font-bold flex items-center gap-1.5 text-rose-900 text-sm mb-1">
                <svg class="w-4 h-4 text-rose-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                Periksa kembali isian formulir:
            </strong>
            @foreach($errors->all() as $error)
                <div class="pl-5">• {{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form 
        action="{{ route('admin.articles.update', $article->id) }}" 
        method="POST" 
        enctype="multipart/form-data" 
        class="space-y-8"
        id="article-edit-form">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">
            
            <!-- KOLOM KIRI: EDITOR KONTEN UTAMA (8/12) -->
            <div class="lg:col-span-8 bg-white border border-slate-200 rounded-2xl p-5 sm:p-7 shadow-xs space-y-6">
                
                <!-- Judul Artikel -->
                <div class="space-y-1.5">
                    <label for="article_title" class="block text-xs font-bold uppercase tracking-wider text-slate-800">
                        Judul Panduan Teknis <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="title" 
                        id="article_title" 
                        x-model="articleTitle"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-base font-semibold text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-[#C2410C]/20 focus:border-[#C2410C] transition-all"
                        required>
                    <div class="text-[11px] text-slate-500 flex items-center gap-1.5 font-mono pt-1">
                        <span class="text-slate-400">Slug URL saat ini:</span>
                        <span class="text-[#C2410C] font-semibold bg-orange-50 px-2 py-0.5 rounded border border-orange-200/60">{{ $article->slug }}</span>
                    </div>
                </div>

                <!-- Excerpt (Ringkasan 2 Kalimat) -->
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between">
                        <label for="excerpt" class="block text-xs font-bold uppercase tracking-wider text-slate-800">
                            Ringkasan Masalah &amp; Solusi (Excerpt) <span class="text-rose-500">*</span>
                        </label>
                        <span class="text-[11px] text-slate-400">Maksimal 2 kalimat padat</span>
                    </div>
                    <textarea 
                        name="excerpt" 
                        id="excerpt" 
                        rows="2" 
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-[#C2410C]/20 focus:border-[#C2410C] transition-all"
                        required>{{ old('excerpt', $article->excerpt) }}</textarea>
                </div>

                <!-- Rich Text Editor dengan Toolbar & Tab Mode -->
                <div class="space-y-2">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-800">
                            Isi Materi Panduan Teknis <span class="text-rose-500">*</span>
                        </label>
                        <div class="flex items-center bg-slate-100 p-0.5 rounded-lg text-xs font-medium self-start sm:self-auto">
                            <button 
                                type="button" 
                                @click="activeTab = 'write'" 
                                :class="activeTab === 'write' ? 'bg-white text-slate-900 shadow-2xs font-bold' : 'text-slate-500 hover:text-slate-900'"
                                class="px-3 py-1 rounded-md transition-all">
                                Mode Tulis
                            </button>
                            <button 
                                type="button" 
                                @click="activeTab = 'preview'" 
                                :class="activeTab === 'preview' ? 'bg-white text-[#C2410C] shadow-2xs font-bold' : 'text-slate-500 hover:text-slate-900'"
                                class="px-3 py-1 rounded-md transition-all">
                                Pratinjau Tampilan
                            </button>
                        </div>
                    </div>

                    <!-- Editor Box -->
                    <div class="border border-slate-200 rounded-xl overflow-hidden shadow-2xs bg-white">
                        
                        <!-- Toolbar Editor -->
                        <div x-show="activeTab === 'write'" class="border-b border-slate-200 bg-slate-50/90 p-2.5 flex flex-wrap items-center gap-1.5 text-xs text-slate-700 select-none">
                            
                            <!-- Headings & Format Dasar -->
                            <div class="flex items-center gap-1 pr-1.5 border-r border-slate-200">
                                <button type="button" @click="insertFormat('<h2>', '</h2>')" class="px-2.5 py-1.5 bg-white hover:bg-slate-100 border border-slate-200 rounded-md font-bold text-slate-800 shadow-2xs transition-colors" title="Heading 2 (Bab Utama)">
                                    H2
                                </button>
                                <button type="button" @click="insertFormat('<h3>', '</h3>')" class="px-2.5 py-1.5 bg-white hover:bg-slate-100 border border-slate-200 rounded-md font-bold text-slate-800 shadow-2xs transition-colors" title="Heading 3 (Rincian)">
                                    H3
                                </button>
                                <button type="button" @click="insertFormat('<strong>', '</strong>')" class="px-2.5 py-1.5 bg-white hover:bg-slate-100 border border-slate-200 rounded-md font-bold text-slate-800 shadow-2xs transition-colors" title="Tebal (Bold)">
                                    B
                                </button>
                                <button type="button" @click="insertFormat('<em>', '</em>')" class="px-2.5 py-1.5 bg-white hover:bg-slate-100 border border-slate-200 rounded-md italic font-serif text-slate-800 shadow-2xs transition-colors" title="Miring (Italic)">
                                    I
                                </button>
                            </div>

                            <!-- List -->
                            <div class="flex items-center gap-1 pr-1.5 border-r border-slate-200">
                                <button type="button" @click="insertList('ul')" class="px-2.5 py-1.5 bg-white hover:bg-slate-100 border border-slate-200 rounded-md text-slate-700 shadow-2xs transition-colors flex items-center gap-1" title="Daftar Titik">
                                    <span>&bull;</span> <span>Bullet</span>
                                </button>
                                <button type="button" @click="insertList('ol')" class="px-2.5 py-1.5 bg-white hover:bg-slate-100 border border-slate-200 rounded-md text-slate-700 shadow-2xs transition-colors flex items-center gap-1" title="Daftar Nomor">
                                    <span>1.</span> <span>Angka</span>
                                </button>
                            </div>

                            <!-- Blok Khusus Edukasi Teknis -->
                            <div class="flex items-center gap-1 pr-1.5 border-r border-slate-200">
                                <button type="button" @click="insertBlock('tools')" class="px-2.5 py-1.5 bg-slate-800 hover:bg-slate-900 text-white rounded-md font-medium text-[11px] shadow-2xs transition-colors flex items-center gap-1" title="Sisipkan Kotak Alat & Spesifikasi Bahan">
                                    <span>🛠️ + Alat &amp; Bahan</span>
                                </button>
                                <button type="button" @click="insertBlock('steps')" class="px-2.5 py-1.5 bg-[#0F4C5C] hover:bg-[#0c3c49] text-white rounded-md font-medium text-[11px] shadow-2xs transition-colors flex items-center gap-1" title="Sisipkan Kotak Langkah Kerja">
                                    <span>📋 + Langkah Kerja</span>
                                </button>
                                <button type="button" @click="insertBlock('warning')" class="px-2.5 py-1.5 bg-[#C2410C] hover:bg-[#9A3412] text-white rounded-md font-medium text-[11px] shadow-2xs transition-colors flex items-center gap-1" title="Sisipkan Kotak Peringatan Teknis">
                                    <span>⚠️ + Peringatan Fatal</span>
                                </button>
                            </div>

                            <!-- Upload Gambar Khusus Editor -->
                            <label class="px-2.5 py-1.5 bg-white hover:bg-slate-100 border border-slate-200 rounded-md cursor-pointer font-medium text-[11px] text-slate-700 shadow-2xs inline-flex items-center gap-1 transition-colors">
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>Unggah Foto</span>
                                <input type="file" accept="image/*" class="hidden" @change="handleImageUpload($event)">
                            </label>
                        </div>

                        <!-- Mode Tulis (Textarea) -->
                        <div x-show="activeTab === 'write'">
                            <textarea 
                                name="content" 
                                id="article-content" 
                                x-model="articleContent"
                                rows="18" 
                                class="w-full p-4 sm:p-5 font-mono text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:bg-slate-50/30 leading-relaxed border-0 resize-y"
                                required></textarea>
                            <div class="px-4 py-2.5 bg-slate-50 border-t border-slate-100 text-[11px] text-slate-500 flex items-center justify-between">
                                <span>Tip: Tag &lt;h2&gt; dan &lt;h3&gt; akan otomatis membentuk Daftar Isi di halaman publik.</span>
                                <span class="font-mono text-slate-400" x-text="(articleContent ? articleContent.length : 0) + ' karakter'"></span>
                            </div>
                        </div>

                        <!-- Mode Pratinjau Tampilan Langsung -->
                        <div x-show="activeTab === 'preview'" class="p-6 sm:p-8 bg-white min-h-[400px]">
                            <div class="prose max-w-none article-prose" x-html="articleContent"></div>
                        </div>

                    </div>
                </div>

            </div>

            <!-- KOLOM KANAN: PENGATURAN METADATA & PUBLIKASI (4/12) -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- KARTU PUBLIKASI -->
                <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 shadow-xs space-y-4">
                    <h3 class="font-display font-bold text-xs uppercase tracking-wider text-slate-900 pb-3 border-b border-slate-100 flex items-center justify-between">
                        <span>Status Publikasi</span>
                        <span class="w-2.5 h-2.5 rounded-full" :class="articleStatus === 'published' ? 'bg-emerald-500 animate-pulse' : (articleStatus === 'scheduled' ? 'bg-blue-500' : 'bg-slate-400')"></span>
                    </h3>

                    <div>
                        <label for="status" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Status Penerbitan
                        </label>
                        <select 
                            name="status" 
                            id="status" 
                            x-model="articleStatus"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:bg-white focus:ring-1 focus:ring-[#C2410C]">
                            <option value="published" {{ $article->status === 'published' ? 'selected' : '' }}>Tayang Sekarang (Published)</option>
                            <option value="scheduled" {{ $article->status === 'scheduled' ? 'selected' : '' }}>Jadwalkan Tayang (Scheduled)</option>
                            <option value="draft" {{ $article->status === 'draft' ? 'selected' : '' }}>Simpan Draf (Draft)</option>
                        </select>
                    </div>

                    <!-- Keterangan Status Langsung -->
                    <div x-show="articleStatus === 'published'" class="p-2.5 rounded-lg bg-emerald-50 border border-emerald-200 text-[11px] text-emerald-800 flex items-start gap-2">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Panduan aktif dan <strong>langsung tampil di website publik</strong>.</span>
                    </div>

                    <!-- Input Jadwal Tayang (hanya aktif jika status = scheduled) -->
                    <div x-show="articleStatus === 'scheduled'" x-cloak class="space-y-1.5 pt-1">
                        <label for="published_at" class="block text-xs font-semibold text-slate-700">
                            Waktu &amp; Tanggal Tayang
                        </label>
                        <input 
                            type="datetime-local" 
                            name="published_at" 
                            id="published_at" 
                            :disabled="articleStatus !== 'scheduled'"
                            value="{{ old('published_at', $article->published_at ? $article->published_at->format('Y-m-d\TH:i') : now()->addDay()->format('Y-m-d\TH:i')) }}"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800">
                        <span class="text-[11px] text-blue-700 block">Panduan akan otomatis tayang saat waktu di atas tercapai.</span>
                    </div>

                    <div x-show="articleStatus === 'draft'" x-cloak class="p-2.5 rounded-lg bg-slate-100 border border-slate-200 text-[11px] text-slate-600">
                        <span>Disimpan sebagai <strong>draf internal</strong>. Belum tampil di website publik.</span>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-3 px-4 bg-[#C2410C] hover:bg-[#9A3412] text-white text-xs font-extrabold uppercase tracking-wider rounded-xl shadow-xs transition-all flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </div>

                <!-- KARTU KATEGORI & TAG -->
                <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 shadow-xs space-y-4">
                    <h3 class="font-display font-bold text-xs uppercase tracking-wider text-slate-900 pb-3 border-b border-slate-100">
                        Kategori &amp; Tag Topik
                    </h3>

                    <div>
                        <label for="category_id" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Kategori Pembahasan <span class="text-rose-500">*</span>
                        </label>
                        <select 
                            name="category_id" 
                            id="category_id" 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:bg-white focus:ring-1 focus:ring-[#C2410C]"
                            required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ (old('category_id', $article->category_id) == $cat->id) ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Pilihan Tag -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-2">
                            Pilih Tag yang Sesuai
                        </label>
                        <div class="max-h-36 overflow-y-auto space-y-1.5 p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                            @php
                                $selectedTagIds = old('tags', $article->tags->pluck('id')->toArray());
                            @endphp
                            @foreach($tags as $t)
                                <label class="flex items-center space-x-2 cursor-pointer select-none hover:text-slate-900 text-slate-700">
                                    <input 
                                        type="checkbox" 
                                        name="tags[]" 
                                        value="{{ $t->id }}" 
                                        {{ in_array($t->id, $selectedTagIds) ? 'checked' : '' }}
                                        class="rounded border-slate-300 text-[#C2410C] focus:ring-[#C2410C]">
                                    <span>#{{ $t->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Input Tambah Tag Baru -->
                    <div>
                        <label for="new_tags" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Tambah Tag Baru (Pisahkan Koma)
                        </label>
                        <input 
                            type="text" 
                            name="new_tags" 
                            id="new_tags" 
                            value="{{ old('new_tags') }}"
                            placeholder="Contoh: Sloof Gantung, Waterproofing" 
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-1 focus:ring-[#C2410C]">
                    </div>
                </div>

                <!-- KARTU THUMBNAIL (GAMBAR SAMPUL) -->
                <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 shadow-xs space-y-4">
                    <h3 class="font-display font-bold text-xs uppercase tracking-wider text-slate-900 pb-3 border-b border-slate-100">
                        Gambar Sampul (Thumbnail)
                    </h3>

                    @if($article->thumbnail_url)
                        <div>
                            <span class="text-xs font-semibold text-slate-500 block mb-1.5">Gambar Sampul Aktif:</span>
                            <img src="{{ $article->thumbnail_url }}" alt="{{ $article->title }}" class="w-full h-36 object-cover rounded-xl border border-slate-200 shadow-2xs">
                        </div>
                    @endif

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Ganti Gambar Sampul
                        </label>
                        <input 
                            type="file" 
                            name="thumbnail" 
                            id="thumbnail" 
                            accept="image/*"
                            @change="previewThumbnail($event)"
                            class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer">
                    </div>

                    <div x-show="thumbUrl" x-cloak class="mt-2 pt-2 border-t border-slate-100">
                        <span class="text-xs font-semibold text-slate-600 block mb-1.5">Pratinjau Gambar Baru:</span>
                        <img :src="thumbUrl" class="w-full h-36 object-cover rounded-xl border border-slate-200 shadow-2xs">
                    </div>
                </div>

            </div>

        </div>

    </form>

</div>
@endsection

@push('scripts')
<script>
window.articleEditor = function() {
    return {
        articleTitle: @js(old('title', $article->title)),
        articleContent: @js(old('content', $article->content)),
        articleStatus: @js(old('status', $article->status)),
        activeTab: 'write',
        thumbUrl: null,

        insertFormat(openTag, closeTag) {
            const textarea = document.getElementById('article-content');
            if (!textarea) return;
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const text = textarea.value;
            const selected = text.substring(start, end) || 'Teks judul atau penjelasan';
            
            textarea.value = text.substring(0, start) + openTag + selected + closeTag + text.substring(end);
            this.articleContent = textarea.value;
            textarea.focus();
            textarea.setSelectionRange(start + openTag.length, start + openTag.length + selected.length);
        },

        insertList(type) {
            const textarea = document.getElementById('article-content');
            if (!textarea) return;
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const text = textarea.value;
            
            let listHtml = '';
            if (type === 'ul') {
                listHtml = '\n<ul>\n    <li>Poin spesifikasi teknis satu</li>\n    <li>Poin spesifikasi teknis dua</li>\n</ul>\n';
            } else {
                listHtml = '\n<ol>\n    <li>Langkah kerja nomor satu</li>\n    <li>Langkah kerja nomor dua</li>\n</ol>\n';
            }

            textarea.value = text.substring(0, start) + listHtml + text.substring(end);
            this.articleContent = textarea.value;
            textarea.focus();
        },

        insertBlock(type) {
            const textarea = document.getElementById('article-content');
            if (!textarea) return;
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const text = textarea.value;

            let blockHtml = '';
            if (type === 'tools') {
                blockHtml = '\n<div class="edu-box-tools">\n    <h3 class="font-semibold text-slate-800 text-base mb-2">Spesifikasi Alat & Komposisi Bahan</h3>\n    <ul class="list-disc list-inside space-y-1 text-slate-700 text-sm">\n        <li>Item alat/bahan 1: Standar spesifikasi...</li>\n        <li>Item alat/bahan 2: Komposisi adukan...</li>\n    </ul>\n</div>\n';
            } else if (type === 'steps') {
                blockHtml = '\n<div class="edu-box-steps">\n    <ol class="list-decimal list-inside space-y-2 text-slate-700 text-sm">\n        <li><strong>Tahap 1:</strong> Deskripsi langkah pengerjaan lapangan...</li>\n        <li><strong>Tahap 2:</strong> Prosedur pemasangan dan penguncian...</li>\n    </ol>\n</div>\n';
            } else if (type === 'warning') {
                blockHtml = '\n<div class="edu-box-warning">\n    <h3 class="font-semibold text-amber-900 text-base mb-1">Peringatan Teknis & Kesalahan Fatal</h3>\n    <p class="text-sm text-slate-700">Hindari kesalahan berikut karena berisiko merusak daya dukung struktur...</p>\n</div>\n';
            }

            textarea.value = text.substring(0, start) + blockHtml + text.substring(end);
            this.articleContent = textarea.value;
            textarea.focus();
        },

        handleImageUpload(event) {
            const file = event.target.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('image', file);
            formData.append('_token', '{{ csrf_token() }}');

            fetch('{{ route('admin.upload.image') }}', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success && data.url) {
                    const imgHtml = `\n<img src="${data.url}" alt="Ilustrasi Teknis Lapangan" class="rounded-lg border border-slate-200 my-4 max-w-full" />\n`;
                    const textarea = document.getElementById('article-content');
                    if (textarea) {
                        const start = textarea.selectionStart;
                        textarea.value = textarea.value.substring(0, start) + imgHtml + textarea.value.substring(start);
                        this.articleContent = textarea.value;
                    }
                } else {
                    alert('Gagal mengunggah foto: ' + (data.message || 'Format tidak didukung'));
                }
            })
            .catch(() => alert('Terjadi gangguan jaringan saat mengunggah foto.'));
        },

        previewThumbnail(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.thumbUrl = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
    };
};

document.addEventListener('alpine:init', () => {
    if (window.Alpine && typeof window.articleEditor === 'function') {
        Alpine.data('articleEditor', window.articleEditor);
    }
});
</script>
@endpush
