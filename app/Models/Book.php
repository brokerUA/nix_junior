<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property integer $id
 * @property string $title
 * @property integer $author_id
 * @property string $description
 * @property integer $category_id
 * @property string $poster
 * @property integer $user_id
 *
 * @property array $searchableFields
 * @property Author $author
 * @property Category $category
 *
 * @property Builder $sort
 * @property Builder $filter
 * @property Builder $search
 */
class Book extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'author_id',
        'description',
        'category_id',
        'poster',
        'user_id',
    ];

    /**
     * @var array
     */
    public $searchableFields = [
        'title',
        'description',
        'author_id',
        'category_id',
        'created_at',
    ];

    /**
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

}
