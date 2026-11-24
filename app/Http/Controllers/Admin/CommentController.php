<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommentController extends Controller
{
    /**
     * Display a listing of comments for moderation.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status', 'pending');

        $query = Comment::with('article');

        if ($status === 'pending') {
            $query->where('is_approved', false);
        } elseif ($status === 'approved') {
            $query->where('is_approved', true);
        }

        $comments = $query->latest()->paginate(15)->withQueryString();
        $pendingCount = Comment::where('is_approved', false)->count();
        $approvedCount = Comment::where('is_approved', true)->count();

        return view('admin.comments.index', compact('comments', 'status', 'pendingCount', 'approvedCount'));
    }

    /**
     * Approve the specified comment.
     */
    public function approve(Comment $comment): RedirectResponse
    {
        $comment->update(['is_approved' => true]);

        return back()->with('success', 'Komentar dari "' . $comment->guest_name . '" berhasil disetujui dan kini tampil publik.');
    }

    /**
     * Remove the specified comment.
     */
    public function destroy(Comment $comment): RedirectResponse
    {
        $guestName = $comment->guest_name;
        $comment->delete();

        return back()->with('success', 'Komentar dari "' . $guestName . '" berhasil dihapus.');
    }
}

