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

    /**
     * @param Builder $query
     * @param string $sort
     * @param string $order
     * @return Builder
     */
    public function scopeSort(Builder $query, string $sort, string $order): Builder
    {
        if ($sort == 'author_id') {
            $externalQuery = Author::select('name')->whereColumn('author_id', 'authors.id');
        }

        if ($sort == 'category_id') {
            $externalQuery = Category::select('name')->whereColumn('category_id', 'categories.id');
        }

        return $query->orderBy(
            $externalQuery ?? $sort,
            $order
        );
    }

    /**
     * @param Builder $query
     * @param string|null $searchQuery
     * @param array $filters
     * @return Builder
     */
    public function scopeSearch(Builder $query, ?string $searchQuery, array $filters): Builder
    {
        if (is_null($searchQuery)) {
            return $query;
        }

        return $query->where(function ($query) use (&$filters, &$searchQuery) {

            $fields = array_diff(
                $this->searchableFields,
                array_keys( array_filter($filters) )
            );

            foreach ($fields as $key => $fieldName) {

                $methodName = $key ? 'orWhere' : 'where';

                switch ($fieldName) {
                    case 'title':
                    case 'description':
                        $query = $query->$methodName($fieldName, 'like', '%' . $searchQuery . '%');
                        break;
                    case 'category_id':
                        $query = $query->$methodName(function ($query) use (&$searchQuery) {
                            return $query->whereHas('category', function (Builder $query) use (&$searchQuery) {
                                $query->where('name', 'like', '%' . $searchQuery . '%');
                            });
                        });
                        break;
                    case 'author_id':
                        $query = $query->$methodName(function ($query) use (&$searchQuery) {
                            return $query->whereHas('author', function (Builder $query) use (&$searchQuery) {
                                $query->where('name', 'like', '%' . $searchQuery . '%');
                            });
                        });
                        break;
                    case 'created_at':
                        $query = $query->$methodName(function ($query) use (&$searchQuery) {
                            return $query->whereDate('created_at', $searchQuery);
                        });
                        break;
                    default:
                }

            }
            return $query;
        });
    }

    /**
     * @param Builder $query
     * @param array $filters
     * @return Builder
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        $filters = array_filter($filters);

        foreach ($filters as $name => $value) {

            if ($name == 'title') {
                $query = $query->where($name, 'like', '%' . $value . '%');
                continue;
            }

            if ($name == 'description') {
                $query = $query->where($name, 'like', '%' . $value . '%');
                continue;
            }

            if ($name == 'author_id') {
                $query = $query->whereHas('author', function (Builder $query) use (&$value) {
                    $query->where('name', 'like', '%' . $value . '%');
                });
                continue;
            }

            if ($name == 'category_id') {
                $query = $query->where('category_id', $value);
                continue;
            }

            if ($name == 'created_at') {
                $query = $query->whereDate('created_at', $value);
            }

        }

        return $query;
    }
}
