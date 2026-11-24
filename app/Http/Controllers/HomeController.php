<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Tag;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the portal landing page with news portal architecture.
     */
    public function index(): View
    {
        // 1. Artikel Utama (Hero Headline)
        $featuredArticle = Article::published()
            ->with(['category', 'tags', 'admin'])
            ->orderByDesc('views_count')
            ->first();

        $excludeIds = $featuredArticle ? [$featuredArticle->id] : [];

        // 2. Sub-Headlines (2 Panduan Fokus di samping/bawah Hero)
        $subFeaturedArticles = Article::published()
            ->whereNotIn('id', $excludeIds)
            ->with(['category', 'tags', 'admin'])
            ->orderByDesc('published_at')
            ->take(2)
            ->get();

        $excludeIds = array_merge($excludeIds, $subFeaturedArticles->pluck('id')->toArray());

        // 3. 5 Edukasi Teratas & Relevan (Format Top 5 Portal Berita)
        $top5Articles = Article::published()
            ->with('category')
            ->orderByDesc('views_count')
            ->orderByDesc('published_at')
            ->take(5)
            ->get();

        // 4. Kategori Topik Pembahasan
        $categories = Category::withCount(['articles' => fn ($q) => $q->published()])
            ->orderBy('name')
            ->get();

        // 5. Feed Aliran Panduan Terbaru (Newsfeed Stream)
        $feedArticles = Article::published()
            ->with(['category', 'tags', 'admin'])
            ->latest('published_at')
            ->take(10)
            ->get();

        // 6. Tanggapan & Diskusi Pembaca Terbaru untuk Widget Sidebar
        $recentComments = Comment::where('is_approved', true)
            ->with('article')
            ->latest()
            ->take(4)
            ->get();

        // 7. Tag Populer untuk Topik Hangat
        $trendingTags = Tag::withCount('articles')
            ->orderByDesc('articles_count')
            ->take(8)
            ->get();

        return view('home', compact(
            'featuredArticle',
            'subFeaturedArticles',
            'top5Articles',
            'categories',
            'feedArticles',
            'recentComments',
            'trendingTags'
        ));
    }
}
