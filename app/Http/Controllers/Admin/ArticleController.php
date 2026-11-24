<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ArticleController extends Controller
{
    /**
     * Display a listing of articles.
     */
    public function index(Request $request): View
    {
        $query = Article::with(['category', 'admin', 'tags'])
            ->withCount(['comments', 'approvedComments']);

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter kategori
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        // Pencarian judul / excerpt
        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('excerpt', 'like', "%{$q}%");
            });
        }

        $articles = $query->latest('created_at')->paginate(10)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.articles.index', compact('articles', 'categories'));
    }

    /**
     * Show the form for creating a new article.
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        return view('admin.articles.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created article in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'excerpt' => ['required', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'status' => ['required', 'in:draft,scheduled,published'],
            'published_at' => ['nullable', 'date'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:tags,id'],
            'new_tags' => ['nullable', 'string', 'max:255'],
        ], [
            'title.required' => 'Judul panduan wajib diisi.',
            'category_id.required' => 'Pilih salah satu kategori.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'excerpt.required' => 'Ringkasan panduan wajib diisi (maksimal 2 kalimat).',
            'content.required' => 'Konten panduan teknis wajib diisi.',
            'status.required' => 'Pilih status publikasi.',
            'thumbnail.image' => 'File thumbnail harus berupa gambar.',
            'thumbnail.max' => 'Ukuran thumbnail maksimal 3MB.',
        ]);

        // Generate slug unik
        $baseSlug = Str::slug($validated['title']);
        $slug = $baseSlug;
        $counter = 1;
        while (Article::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        // Upload thumbnail jika ada
        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/thumbnails'), $fileName);
            $thumbnailPath = 'uploads/thumbnails/' . $fileName;
        }

        // Tentukan tanggal publikasi secara presisi:
        // Jika status "published" (Tayang Sekarang) -> published_at WAJIB NOW agar langsung tampil di frontend
        if ($validated['status'] === 'published') {
            $publishedAt = now();
        } elseif ($validated['status'] === 'scheduled') {
            $publishedAt = !empty($validated['published_at']) ? Carbon::parse($validated['published_at']) : now()->addDay();
        } else {
            $publishedAt = null;
        }

        $adminId = Auth::guard('admin')->id() ?: (Admin::value('id') ?? 1);

        $article = Article::create([
            'admin_id' => $adminId,
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'slug' => $slug,
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'],
            'thumbnail' => $thumbnailPath,
            'status' => $validated['status'],
            'published_at' => $publishedAt,
            'views_count' => 0,
        ]);

        // Proses tags yang dipilih
        $tagIds = $validated['tags'] ?? [];

        // Tambah tag baru langsung dari input jika ada
        if (!empty($validated['new_tags'])) {
            $newTagNames = array_map('trim', explode(',', $validated['new_tags']));
            foreach ($newTagNames as $tagName) {
                if (!empty($tagName)) {
                    $tagSlug = Str::slug($tagName);
                    $tag = Tag::firstOrCreate(['slug' => $tagSlug], ['name' => $tagName]);
                    $tagIds[] = $tag->id;
                }
            }
        }

        if (!empty($tagIds)) {
            $article->tags()->sync(array_unique($tagIds));
        }

        $redirect = redirect()->route('admin.articles.index')
            ->with('success', 'Panduan teknis "' . $article->title . '" berhasil diterbitkan dan langsung terhubung ke website publik!');

        if ($article->status === 'published') {
            $redirect->with('live_article_url', route('articles.show', $article->slug));
        }

        return $redirect;
    }

    /**
     * Show the form for editing the specified article.
     */
    public function edit(Article $article): View
    {
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        $selectedTagIds = $article->tags->pluck('id')->toArray();

        return view('admin.articles.edit', compact('article', 'categories', 'tags', 'selectedTagIds'));
    }

    /**
     * Update the specified article in storage.
     */
    public function update(Request $request, Article $article): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'excerpt' => ['required', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'status' => ['required', 'in:draft,scheduled,published'],
            'published_at' => ['nullable', 'date'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:tags,id'],
            'new_tags' => ['nullable', 'string', 'max:255'],
        ], [
            'title.required' => 'Judul panduan wajib diisi.',
            'category_id.required' => 'Pilih salah satu kategori.',
            'excerpt.required' => 'Ringkasan panduan wajib diisi.',
            'content.required' => 'Konten panduan teknis wajib diisi.',
            'status.required' => 'Pilih status publikasi.',
        ]);

        // Perbarui slug jika judul berubah
        if ($article->title !== $validated['title']) {
            $baseSlug = Str::slug($validated['title']);
            $slug = $baseSlug;
            $counter = 1;
            while (Article::where('slug', $slug)->where('id', '!=', $article->id)->exists()) {
                $slug = "{$baseSlug}-{$counter}";
                $counter++;
            }
            $article->slug = $slug;
        }

        // Upload thumbnail baru jika ada
        if ($request->hasFile('thumbnail')) {
            if ($article->thumbnail && file_exists(public_path($article->thumbnail))) {
                @unlink(public_path($article->thumbnail));
            }

            $file = $request->file('thumbnail');
            $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/thumbnails'), $fileName);
            $article->thumbnail = 'uploads/thumbnails/' . $fileName;
        }

        // Tanggal publikasi
        if ($validated['status'] === 'published') {
            $publishedAt = ($article->published_at && $article->published_at <= now()) ? $article->published_at : now();
        } elseif ($validated['status'] === 'scheduled') {
            $publishedAt = !empty($validated['published_at']) ? Carbon::parse($validated['published_at']) : ($article->published_at ?: now()->addDay());
        } else {
            $publishedAt = null;
        }

        $article->category_id = $validated['category_id'];
        $article->title = $validated['title'];
        $article->excerpt = $validated['excerpt'];
        $article->content = $validated['content'];
        $article->status = $validated['status'];
        $article->published_at = $publishedAt;
        $article->save();

        // Sync tags
        $tagIds = $validated['tags'] ?? [];
        if (!empty($validated['new_tags'])) {
            $newTagNames = array_map('trim', explode(',', $validated['new_tags']));
            foreach ($newTagNames as $tagName) {
                if (!empty($tagName)) {
                    $tagSlug = Str::slug($tagName);
                    $tag = Tag::firstOrCreate(['slug' => $tagSlug], ['name' => $tagName]);
                    $tagIds[] = $tag->id;
                }
            }
        }

        $article->tags()->sync(array_unique($tagIds));

        $redirect = redirect()->route('admin.articles.index')
            ->with('success', 'Perubahan pada panduan "' . $article->title . '" berhasil disimpan dan diperbarui di website publik.');

        if ($article->status === 'published') {
            $redirect->with('live_article_url', route('articles.show', $article->slug));
        }

        return $redirect;
    }

    /**
     * Remove the specified article from storage.
     */
    public function destroy(Article $article): RedirectResponse
    {
        if ($article->thumbnail && file_exists(public_path($article->thumbnail))) {
            @unlink(public_path($article->thumbnail));
        }

        $title = $article->title;
        $article->delete();

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel "' . $title . '" berhasil dihapus secara permanen.');
    }
}
