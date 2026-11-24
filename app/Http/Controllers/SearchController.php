<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    /**
     * Search and filter articles.
     */
    public function index(Request $request): View
    {
        $query = Article::published()->with(['category', 'tags']);

        // Kata kunci pencarian judul, ringkasan, atau isi
        $q = trim((string) $request->input('q', ''));
        if (!empty($q)) {
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('excerpt', 'like', "%{$q}%")
                    ->orWhere('content', 'like', "%{$q}%");
            });
        }

        // Filter berdasarkan kategori (slug)
        $categorySlug = $request->input('category');
        $selectedCategory = null;
        if (!empty($categorySlug)) {
            $selectedCategory = Category::where('slug', $categorySlug)->first();
            if ($selectedCategory) {
                $query->where('category_id', $selectedCategory->id);
            }
        }

        // Filter berdasarkan tag (slug)
        $tagSlug = $request->input('tag');
        $selectedTag = null;
        if (!empty($tagSlug)) {
            $selectedTag = Tag::where('slug', $tagSlug)->first();
            if ($selectedTag) {
                $query->whereHas('tags', function ($t) use ($selectedTag) {
                    $t->where('tags.id', $selectedTag->id);
                });
            }
        }

        // Pengurutan
        $sort = $request->input('sort', 'terbaru');
        if ($sort === 'terpopuler') {
            $query->orderByDesc('views_count')->orderByDesc('published_at');
        } else {
            $query->latest('published_at');
        }

        $articles = $query->paginate(9)->withQueryString();
        $categories = Category::withCount(['articles' => fn ($sub) => $sub->published()])->get();
        $tags = Tag::withCount('articles')->orderByDesc('articles_count')->take(15)->get();

        return view('search', compact(
            'articles',
            'categories',
            'tags',
            'q',
            'selectedCategory',
            'selectedTag',
            'sort'
        ));
    }
}

