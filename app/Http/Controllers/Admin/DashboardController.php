<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(): View
    {
        $totalPublished = Article::where('status', 'published')->count();
        $totalDraft = Article::where('status', 'draft')->count();
        $totalScheduled = Article::where('status', 'scheduled')->count();
        $totalViews = Article::sum('views_count');
        $pendingCommentsCount = Comment::where('is_approved', false)->count();

        // 5 artikel dengan pembaca terbanyak
        $topArticles = Article::with(['category', 'admin'])
            ->orderByDesc('views_count')
            ->take(5)
            ->get();

        // Komentar menunggu moderasi terbaru
        $recentPendingComments = Comment::with('article')
            ->where('is_approved', false)
            ->latest()
            ->take(5)
            ->get();

        // 5 artikel terbaru yang diperbarui/dibuat
        $recentArticles = Article::with(['category', 'admin'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalPublished',
            'totalDraft',
            'totalScheduled',
            'totalViews',
            'pendingCommentsCount',
            'topArticles',
            'recentPendingComments',
            'recentArticles'
        ));
    }
}

