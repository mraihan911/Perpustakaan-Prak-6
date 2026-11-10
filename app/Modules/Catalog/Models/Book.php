<?php

namespace Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Catalog\ValueObjects\ISBN;

class Book extends Model
{
    use SoftDeletes;

    protected $table = 'library_books';

    protected $fillable = [
        'title',
        'isbn',
        'publisher_id',
        'stock',
        'available_stock',
        'published_year',
    ];

    protected $casts = [
        'stock' => 'integer',
        'available_stock' => 'integer',
        'published_year' => 'integer',
    ];

    public function setIsbnAttribute($v): void
    {
        $this->attributes['isbn'] = (string) new ISBN($v);
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class, 'publisher_id');
    }

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'author_book', 'book_id', 'author_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'book_category', 'book_id', 'category_id')->withPivot('added_at');
    }

    public function scopeAvailable(Builder $q): Builder
    {
        return $q->where('available_stock', '>', 0);
    }

    public function scopeSearch(Builder $q, string $term): Builder
    {
        return $q->where(fn($x) => $x->where('title', 'like', "%{$term}%")->orWhere('isbn', 'like', "%{$term}%"));
    }

    public function scopeInCategory(Builder $q, int $id): Builder
    {
        return $q->whereHas('categories', fn($x) => $x->where('library_categories.id', $id));
    }
}