<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexBookRequest;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Services\BookService;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;

class BookController extends Controller
{
    /**
     * @var BookService
     */
    private $service;

    public function __construct(BookService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @param IndexBookRequest $request
     * @return mixed
     */
    public function index(IndexBookRequest $request)
    {
        $page = $request->input('page');

        if ($page == 1) {
            return redirect()
                ->route('books.index', $request->except(['page']));
        }

        $books = $this->service->getFilteredWithPaginate($request, 5);

        $categories = Category::select(['id', 'name'])->orderBy('name')->get();

        return view('book.index', compact(
            'books',
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreBookRequest $request): RedirectResponse
    {
        $this->service->save($request);

        return redirect()
            ->route('books.index')
            ->with('message', 'Book success created.');
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
     * @param \App\Http\Requests\UpdateBookRequest $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateBookRequest $request, int $id): RedirectResponse
    {
        $this->service->update($request, $id);

        return redirect()
            ->route('books.index')
            ->with('message', 'Book updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->service->delete($id);

        return redirect()
            ->route('books.index')
            ->with('message', 'Book deleted.');
    }
}
