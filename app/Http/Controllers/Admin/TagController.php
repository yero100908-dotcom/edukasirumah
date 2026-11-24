<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TagController extends Controller
{
    /**
     * Display a listing of tags.
     */
    public function index(): View
    {
        $tags = Tag::withCount('articles')->orderBy('name')->get();

        return view('admin.tags.index', compact('tags'));
    }

    /**
     * Store a newly created tag.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:tags,name'],
        ], [
            'name.required' => 'Nama tag wajib diisi.',
            'name.unique' => 'Tag tersebut sudah terdaftar.',
        ]);

        Tag::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag baru berhasil ditambahkan.');
    }

    /**
     * Remove the specified tag.
     */
    public function destroy(Tag $tag): RedirectResponse
    {
        $tagName = $tag->name;
        $tag->articles()->detach();
        $tag->delete();

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag "' . $tagName . '" berhasil dihapus.');
    }
}

