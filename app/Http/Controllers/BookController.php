<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return mixed
     */
    public function index()
    {
        $page = request()->get('page');

        if ($page == 1) {
            return redirect()
                ->route('book.index', request()->except(['page']));
        }

        $categories = Category::orderBy('name')->get();

        $books = new Book();

        /*
         * Sort
         * */

        $requestSort = request('sort', 'created_at');
        $requestOrder = request('order', 'desc');

        if ($requestSort == 'author') {
            $externalQuery = Author::select('name')->whereColumn('author_id', 'authors.id');
        }

        if ($requestSort == 'category') {
            $externalQuery = Category::select('name')->whereColumn('category_id', 'categories.id');
        }

        $books = $books->orderBy(
            $externalQuery ?? $requestSort,
            $requestOrder
        );


        /*
         * Filter
         * */

        $filters = array_filter(request('filter', []));

        foreach ($filters as $name => $value) {

            if ($name == 'title') {
                $books = $books->where($name, 'like', '%' . $value . '%');
            }

            if ($name == 'description') {
                $books = $books->where($name, 'like', '%' . $value . '%');
            }

            if ($name == 'author') {
                $books = $books->whereHas('author', function (Builder $query) use (&$value) {
                    $query->where('name', 'like', '%' . $value . '%');
                });
            }

            if ($name == 'category') {
                $books = $books->where('category_id', $value);
            }

            if ($name == 'date') {
                $books = $books->whereDate('created_at', $value);
            }

        }

        $books = $books->paginate(5);


        return view('book.index', compact(
            'books',
            'requestSort',
            'requestOrder',
            'categories',
        ));
    }

    public function search()
    {
        $page = request()->get('page');

        if ($page == 1) {
            return redirect()
                ->route('book.search', request()->except(['page']));
        }

        $books = Book::search(request('query'))
            ->paginate(5);

        return view('book.search', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return mixed
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * @param  \App\Models\Book  $books
     * @return \Illuminate\Http\Response
     */
    public function edit(Book $books)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book  $books
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Book $books)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Book  $books
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $books)
    {
        //
    }
}
