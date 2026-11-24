<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Store a guest comment on the article.
     */
    public function store(Request $request, Article $article): RedirectResponse
    {
        $validated = $request->validate([
            'guest_name' => ['required', 'string', 'max:80'],
            'guest_email' => ['required', 'email', 'max:120'],
            'body' => ['required', 'string', 'min:5', 'max:1000'],
        ], [
            'guest_name.required' => 'Nama lengkap wajib diisi.',
            'guest_email.required' => 'Alamat email wajib diisi.',
            'guest_email.email' => 'Format email tidak valid.',
            'body.required' => 'Tuliskan pertanyaan atau tanggapan Anda.',
            'body.min' => 'Pertanyaan minimal 5 karakter.',
            'body.max' => 'Pertanyaan maksimal 1000 karakter.',
        ]);

        Comment::create([
            'article_id' => $article->id,
            'guest_name' => $validated['guest_name'],
            'guest_email' => $validated['guest_email'],
            'body' => $validated['body'],
            'is_approved' => false, // Default false demi moderasi anti-spam
        ]);

        return redirect()->to(url()->previous() . '#kolom-diskusi')
            ->with('comment_success', 'Terima kasih atas tanggapan Anda! Komentar telah terkirim dan akan segera ditinjau oleh tim redaksi teknis kami sebelum ditampilkan.');
    }
}

