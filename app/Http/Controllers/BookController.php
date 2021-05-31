<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexBookRequest;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Services\BookService;
use App\Http\Services\CategoryService;
use App\Http\Services\AuthorService;
use App\Models\Book;
use Illuminate\Http\RedirectResponse;

class BookController extends Controller
{
    /**
     * @var BookService
     */
    private $bookService;

    /**
     * @var CategoryService
     */
    private $categoryService;

    /**
     * @var AuthorService
     */
    private $authorService;

    public function __construct(
        BookService $bookService,
        CategoryService $categoryService,
        AuthorService $authorService
    ) {
        $this->bookService = $bookService;
        $this->categoryService = $categoryService;
        $this->authorService = $authorService;
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

        $books = $this->bookService->getFilteredWithPaginate($request, 5);

        $categories = $this->categoryService->getAll();

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
        $categories = $this->categoryService->getAll();

        $authors = $this->authorService->getAll();

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
        $this->bookService->save($request);

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
        $categories = $this->categoryService->getAll();

        $authors = $this->authorService->getAll();

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
        $this->bookService->update($request, $id);

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
        $this->bookService->delete($id);

        return redirect()
            ->route('books.index')
            ->with('message', 'Book deleted.');
    }
}
