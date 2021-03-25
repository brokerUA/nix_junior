<?php

namespace App\Http\Services;

use App\Http\Requests\IndexBookRequest;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Author;
use App\Models\Book;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class BookService
{
    private $model;

    public function __construct(Book $model)
    {
        $this->model = $model;
    }

    public function getFilteredWithPaginate(IndexBookRequest $request, int $paginateCount): LengthAwarePaginator
    {
        return $this->model
            ->sort(
                $request->input('sort', 'created_at'),
                $request->input('order', 'desc')
            )
            ->search(
                $request->input('query'),
                $request->input('filter', [])
            )
            ->filter(
                $request->input('filter', [])
            )
            ->paginate(
                $paginateCount
            );
    }

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

    public function delete(int $id): bool
    {
        $book = $this->model->find($id);

        if ($book->poster) {
            Storage::delete($book->poster);
        }

        return $book->delete();
    }
}
