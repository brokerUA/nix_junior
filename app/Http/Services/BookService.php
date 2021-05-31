<?php

namespace App\Http\Services;

use App\Http\Requests\IndexBookRequest;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Traits\ServiceTrait;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

/**
 * Class BookService
 * @package App\Http\Services
 */
class BookService
{
    use ServiceTrait;

    /**
     * @var Book
     */
    private $model;

    /**
     * @param Builder $query
     * @param string $sort
     * @param string $order
     * @return Builder
     */
    protected function scopeSort(Builder $query, string $sort, string $order): Builder
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
    protected function scopeSearch(Builder $query, ?string $searchQuery, array $filters): Builder
    {
        if (is_null($searchQuery)) {
            return $query;
        }

        return $query->where(function ($query) use (&$filters, &$searchQuery) {

            $fields = array_diff(
                $this->model->searchableFields,
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
    protected function scopeFilter(Builder $query, array $filters): Builder
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

    /**
     * BookService constructor.
     * @param Book $model
     */
    public function __construct(Book $model)
    {
        $this->model = $model;
    }

    /**
     * @param IndexBookRequest $request
     * @param int $paginateCount
     * @return LengthAwarePaginator
     */
    public function getFilteredWithPaginate(IndexBookRequest $request, int $paginateCount): LengthAwarePaginator
    {
        return $this->buildFilteredWithPaginate($request, $paginateCount);
    }

    /**
     * @param StoreBookRequest $request
     * @return Book
     */
    public function save(StoreBookRequest $request): Book
    {
        $author = Author::firstOrCreate(
            ['name' => $request->input('author')]
        );
        $this->model->author_id = $author->id;

        if ($request->hasFile('poster')) {
            $this->model->poster = $request->file('poster')->store('posters');
        }

        $this->model->title = $request->input('title');
        $this->model->description = $request->input('description');
        $this->model->category_id = $request->input('category_id');
        $this->model->user_id = auth()->user()->id;
        $this->model->save();

        return $this->model;
    }

    /**
     * @param UpdateBookRequest $request
     * @param int $id
     * @return Book
     */
    public function update(UpdateBookRequest $request, int $id): Book
    {
        $book = $this->model->find($id);

        $author = Author::firstOrCreate(
            ['name' => $request->input('author')]
        );
        $book->author_id = $author->id;

        if ($request->hasFile('poster')) {
            $book->poster = $request->file('poster')->store('posters');
        }

        $book->title = $request->input('title');
        $book->description = $request->input('description');
        $book->category_id = $request->input('category_id');
        $book->save();

        return $book;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $book = $this->model->find($id);

        if ($book->poster) {
            Storage::delete($book->poster);
        }

        return $book->delete();
    }
}
