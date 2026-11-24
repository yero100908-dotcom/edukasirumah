<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'admin_id',
        'category_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'thumbnail',
        'status',
        'views_count',
        'published_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'views_count' => 'integer',
            'published_at' => 'datetime',
        ];
    }

    /**
     * Boot the model.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Article $article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
        });
    }

    /**
     * Author relation.
     *
     * @return BelongsTo<Admin, $this>
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    /**
     * Category relation.
     *
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Tags relation.
     *
     * @return BelongsToMany<Tag, $this>
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'article_tag');
    }

    /**
     * Comments relation.
     *
     * @return HasMany<Comment, $this>
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'article_id');
    }

    /**
     * Approved comments relation.
     *
     * @return HasMany<Comment, $this>
     */
    public function approvedComments(): HasMany
    {
        return $this->hasMany(Comment::class, 'article_id')->where('is_approved', true);
    }

    /**
     * Scope a query to only include published articles.
     *
     * @param  Builder<Article>  $query
     * @return Builder<Article>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->where(function (Builder $q) {
                $q->whereNull('published_at')
                  ->orWhere('published_at', '<=', now());
            });
    }

    /**
     * Calculate estimated reading time in minutes.
     */
    public function getReadingTimeAttribute(): int
    {
        $wordCount = str_word_count(strip_tags($this->content));
        $minutes = (int) ceil($wordCount / 180);

        return max(1, $minutes);
    }

    /**
     * Get clean thumbnail URL.
     */
    public function getThumbnailUrlAttribute(): string
    {
        if (!empty($this->thumbnail)) {
            if (str_starts_with($this->thumbnail, 'http://') || str_starts_with($this->thumbnail, 'https://')) {
                return $this->thumbnail;
            }
            if (file_exists(public_path($this->thumbnail))) {
                return asset($this->thumbnail);
            }
            if (file_exists(public_path('uploads/' . $this->thumbnail))) {
                return asset('uploads/' . $this->thumbnail);
            }
            if (file_exists(public_path('storage/' . $this->thumbnail))) {
                return asset('storage/' . $this->thumbnail);
            }
            return asset($this->thumbnail);
        }

        // Fallback otomatis visual sesuai kategori agar selalu tampil profesional
        $categorySlug = $this->category?->slug ?? '';
        if (str_contains($categorySlug, 'pondasi') || str_contains($categorySlug, 'konstruksi')) {
            return asset('uploads/thumbnails/pondasi-batu-kali.svg');
        }
        if (str_contains($categorySlug, 'keramik') || str_contains($categorySlug, 'lantai')) {
            return asset('uploads/thumbnails/keramik-popping.svg');
        }
        if (str_contains($categorySlug, 'pipa') || str_contains($categorySlug, 'sanitasi')) {
            return asset('uploads/thumbnails/kemiringan-pipa.svg');
        }
        if (str_contains($categorySlug, 'perawatan') || str_contains($categorySlug, 'rumah')) {
            return asset('uploads/thumbnails/limbah-dapur.svg');
        }

        return asset('uploads/thumbnails/pondasi-batu-kali.svg');
    }
}
