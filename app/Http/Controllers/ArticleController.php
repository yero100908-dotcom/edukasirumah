<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ArticleController extends Controller
{
    /**
     * Display the specified article details.
     */
    public function show(Request $request, string $slug): View
    {
        $article = Article::published()
            ->where('slug', $slug)
            ->with(['category', 'tags', 'admin', 'approvedComments'])
            ->firstOrFail();

        // Safe increment views_count menggunakan session agar tidak terhitung ganda saat refresh
        $sessionKey = 'viewed_article_' . $article->id;
        if (!session()->has($sessionKey)) {
            $article->increment('views_count');
            session()->put($sessionKey, true);
        }

        // Generate Daftar Isi Otomatis (TOC) dari tag <h2> dan <h3>
        $toc = [];
        $content = $article->content;
        $domPattern = '/<(h[23])(.*?)>(.*?)<\/\1>/i';
        $parsedContent = preg_replace_callback($domPattern, function ($matches) use (&$toc) {
            $tag = strtolower($matches[1]);
            $attrs = $matches[2];
            $innerHtml = $matches[3];
            $plainText = trim(strip_tags($innerHtml));
            $anchorId = Str::slug($plainText);

            if (empty($anchorId)) {
                $anchorId = 'bagian-' . (count($toc) + 1);
            }

            $toc[] = [
                'level' => $tag === 'h2' ? 2 : 3,
                'title' => $plainText,
                'id' => $anchorId,
            ];

            // Sisipkan id anchor jika belum ada
            if (!str_contains($attrs, 'id=')) {
                return "<{$tag}{$attrs} id=\"{$anchorId}\">{$innerHtml}</{$tag}>";
            }

            return $matches[0];
        }, $content);

        // Sidebar: 5 Berita / Panduan Teratas yang Paling Relevan
        // Prioritas 1: Kategori yang sama
        $relevantArticles = Article::published()
            ->where('id', '!=', $article->id)
            ->where('category_id', $article->category_id)
            ->with('category')
            ->orderByDesc('views_count')
            ->orderByDesc('published_at')
            ->take(5)
            ->get();

        // Prioritas 2: Jika kurang dari 5, lengkapi dari tag yang sama
        if ($relevantArticles->count() < 5) {
            $tagIds = $article->tags->pluck('id')->toArray();
            $needed = 5 - $relevantArticles->count();
            $alreadyIds = $relevantArticles->pluck('id')->push($article->id)->toArray();

            $fromTags = Article::published()
                ->whereNotIn('id', $alreadyIds)
                ->whereHas('tags', fn ($q) => $q->whereIn('tags.id', $tagIds))
                ->with('category')
                ->orderByDesc('views_count')
                ->orderByDesc('published_at')
                ->take($needed)
                ->get();

            $relevantArticles = $relevantArticles->merge($fromTags);
        }

        // Prioritas 3: Jika masih kurang dari 5, backfill dengan artikel terpopuler lintas kategori
        if ($relevantArticles->count() < 5) {
            $needed = 5 - $relevantArticles->count();
            $alreadyIds = $relevantArticles->pluck('id')->push($article->id)->toArray();

            $backfill = Article::published()
                ->whereNotIn('id', $alreadyIds)
                ->with('category')
                ->orderByDesc('views_count')
                ->orderByDesc('published_at')
                ->take($needed)
                ->get();

            $relevantArticles = $relevantArticles->merge($backfill);
        }

        // Artikel Terkait (3 artikel di bagian bawah konten)
        $relatedArticles = Article::published()
            ->where('id', '!=', $article->id)
            ->where('category_id', $article->category_id)
            ->with(['category', 'tags'])
            ->latest('published_at')
            ->take(3)
            ->get();

        $comments = $article->approvedComments()->latest()->get();

        return view('articles.show', compact(
            'article',
            'parsedContent',
            'toc',
            'relevantArticles',
            'relatedArticles',
            'comments'
        ));
    }
}

