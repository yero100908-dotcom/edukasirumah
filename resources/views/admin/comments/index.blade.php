@extends('layouts.admin')

@section('title', 'Moderasi Diskusi & Komentar — EdukasiRumahan')
@section('page_title', 'Moderasi Komentar & Tanya Jawab')

@section('content')
<div class="space-y-6">

    <!-- TAB FILTER STATUS MODERASI -->
    <div class="flex items-center space-x-2 border-b border-slate-200 pb-3 text-xs font-semibold">
        <a href="{{ route('admin.comments.index', ['status' => 'pending']) }}" class="px-4 py-2 rounded-lg transition-colors flex items-center space-x-2 {{ $status === 'pending' ? 'bg-[#C2410C] text-white' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' }}">
            <span>Menunggu Moderasi</span>
            @if($pendingCount > 0)
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $status === 'pending' ? 'bg-white text-[#C2410C]' : 'bg-amber-500 text-white' }}">
                    {{ $pendingCount }}
                </span>
            @endif
        </a>

        <a href="{{ route('admin.comments.index', ['status' => 'approved']) }}" class="px-4 py-2 rounded-lg transition-colors flex items-center space-x-2 {{ $status === 'approved' ? 'bg-[#C2410C] text-white' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' }}">
            <span>Telah Disetujui</span>
            <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $status === 'approved' ? 'bg-white text-[#C2410C]' : 'bg-slate-200 text-slate-700' }}">
                {{ $approvedCount }}
            </span>
        </a>

        <a href="{{ route('admin.comments.index', ['status' => 'all']) }}" class="px-4 py-2 rounded-lg transition-colors {{ $status === 'all' ? 'bg-[#C2410C] text-white' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' }}">
            <span>Semua Diskusi</span>
        </a>
    </div>

    <!-- DAFTAR KOMENTAR -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-2xs divide-y divide-slate-100">
        @forelse($comments as $comment)
            <div class="p-6 space-y-3 hover:bg-slate-50/60 transition-colors">
                
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs">
                    <div class="flex items-center space-x-2">
                        <span class="w-7 h-7 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-700 text-xs">
                            {{ substr($comment->guest_name, 0, 1) }}
                        </span>
                        <div>
                            <span class="font-bold text-slate-900 text-sm">{{ $comment->guest_name }}</span>
                            <span class="text-slate-400 font-normal">({{ $comment->guest_email }})</span>
                        </div>
                        @if($comment->is_approved)
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 ml-2">
                                Tayang Publik
                            </span>
                        @else
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-50 text-amber-800 border border-amber-200 ml-2">
                                Menunggu Persetujuan
                            </span>
                        @endif
                    </div>

                    <div class="text-slate-400 text-[11px]">
                        {{ $comment->created_at->locale('id')->isoFormat('D MMMM Y, HH:mm') }}
                    </div>
                </div>

                <!-- Isi Komentar -->
                <div class="p-4 bg-slate-50 border border-slate-200/80 rounded-lg text-xs sm:text-sm text-slate-700 leading-relaxed font-sans">
                    {{ $comment->body }}
                </div>

                <!-- Artikel Terkait & Tombol Tindakan -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs pt-1">
                    <div class="text-slate-500">
                        Pada Panduan: 
                        @if($comment->article)
                            <a href="{{ route('articles.show', $comment->article->slug) }}" target="_blank" class="font-semibold text-slate-800 hover:text-[#C2410C] underline">
                                {{ $comment->article->title }}
                            </a>
                        @else
                            <span class="text-slate-400">(Artikel telah dihapus)</span>
                        @endif
                    </div>

                    <div class="flex items-center space-x-2 self-end sm:self-auto shrink-0">
                        @if(!$comment->is_approved)
                            <form action="{{ route('admin.comments.approve', $comment->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1.5 shadow-xs">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>Setujui Tayang</span>
                                </button>
                            </form>
                        @endif

                        <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tanggapan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1.5 bg-slate-100 hover:bg-rose-50 hover:text-rose-600 text-slate-600 rounded-lg text-xs font-semibold transition-colors">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        @empty
            <div class="p-12 text-center text-slate-400 text-xs">
                Tidak ada komentar dalam status ini.
            </div>
        @endforelse
    </div>

    <!-- Paginasi -->
    <div class="pt-4">
        {{ $comments->links() }}
    </div>

</div>
@endsection

