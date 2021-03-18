<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexBookRequest;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param IndexBookRequest $request
     * @return mixed
     */
    public function index(IndexBookRequest $request)
    {

        $page = request('page');

        if ($page == 1) {
            return redirect()
                ->route('books.index', request()->except(['page']));
        }

        $categories = Category::orderBy('name')->get();

        $model = new Book;

        /*
         * Search
         * */

        if (request()->filled('query')) {

            $model = $model->where(function ($query) use (&$model) {
                foreach ($model->searchableFields as $key => $fieldName) {
                    $methodName = $key ? 'orWhere' : 'where';

                    if (in_array($fieldName, ['title', 'description', 'category_id'])) {
                        $query = $query->$methodName($fieldName, 'like', '%' . request('query') . '%');
                    }

                    if ($fieldName == 'author_id') {
                        $query = $query->$methodName(function ($query) {
                            return $query->whereHas('author', function (Builder $query) {
                                $query->where('name', 'like', '%' . request('query') . '%');
                            });
                        });
                    }

                    if ($fieldName == 'created_at') {
                        $query = $query->$methodName(function ($query) {
                            return $query->whereDate('created_at', request('query'));
                        });
                    }

                }
                return $query;
            });

        }

        /*
        * Sort
        * */

        $requestSort = request('sort', 'created_at');
        $requestOrder = request('order', 'desc');

        if ($requestSort == 'author_id') {
            $externalQuery = Author::select('name')->whereColumn('author_id', 'authors.id');
        }

        if ($requestSort == 'category_id') {
            $externalQuery = Category::select('name')->whereColumn('category_id', 'categories.id');
        }

        $model = $model->orderBy(
            $externalQuery ?? $requestSort,
            $requestOrder
        );

        /*
         * Filter
         * */

        $filters = array_filter(request('filter', []));

        foreach ($filters as $name => $value) {

            if ($name == 'title') {
                $model = $model->where($name, 'like', '%' . $value . '%');
            }

            if ($name == 'description') {
                $model = $model->where($name, 'like', '%' . $value . '%');
            }

            if ($name == 'author_id') {
                $model = $model->whereHas('author', function (Builder $query) use (&$value) {
                    $query->where('name', 'like', '%' . $value . '%');
                });
            }

            if ($name == 'category_id') {
                $model = $model->where('category_id', $value);
            }

            if ($name == 'created_at') {
                $model = $model->whereDate('created_at', $value);
            }

        }

        $books = $model->paginate(5);

        return view('book.index', compact(
            'books',
            'requestSort',
            'requestOrder',
            'categories',
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return mixed
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();

        $authors = Author::orderBy('name')->get();

        return view('book.create', compact('categories', 'authors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreBookRequest $request
     * @param Book $book
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function store(StoreBookRequest $request, Book $book)
    {
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
        $book->user_id = auth()->user()->id;
        $book->save();

        return redirect()
            ->route('books.index')
            ->with('message', 'Book success created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $books
     * @return \Illuminate\Http\Response
     */
    public function show(Book $books)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit(Book $book)
    {
        $categories = Category::orderBy('name')->get();

        $authors = Author::orderBy('name')->get();

        return view('book.edit', compact('book', 'categories', 'authors'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBookRequest  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
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

        return redirect()
            ->route('books.index')
            ->with('message', 'Book updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Book $book
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Book $book)
    {
        if ($book->poster) {
            Storage::delete($book->poster);
        }

        $book->delete();

        return redirect()
            ->route('books.index')
            ->with('message', 'Book deleted.');
    }
}
