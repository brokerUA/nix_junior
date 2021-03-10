<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return mixed
     */
    public function index()
    {
        $validator = Validator::make(request()->all(), [
            'page' => 'nullable|integer|min:1',
            'action' => ['nullable', Rule::in(['search', 'filter'])],
            'query' => 'nullable|required_if:action,search|string|max:100',
            'sort' => 'nullable|string',
            'order' => ['nullable', Rule::in(['asc', 'desc'])],
            'filter' => 'nullable|array',
            'filter.title' => 'nullable|string',
            'filter.description' => 'nullable|string',
            'filter.author' => 'nullable|string',
            'filter.category' => 'nullable|integer|min:1',
            'filter.date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('book.index')
                ->withErrors($validator)
                ->withInput();
        }

        $page = request('page');

        if ($page == 1) {
            return redirect()
                ->route('book.index', request()->except(['page']));
        }

        $requestSort = request('sort', 'created_at');
        $requestOrder = request('order', 'desc');

        $categories = Category::orderBy('name')->get();

        $books = new Book;

        if (request('action') == 'search') {
            $books = $books->search(request('query'));
        }

        if (request('action') == 'filter') {

            /*
             * Sort
             * */

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

        }

        $books = $books->paginate(5);

        return view('book.index', compact(
            'books',
            'requestSort',
            'requestOrder',
            'categories',
        ));
    }

    private function search()
    {
        $books = Book::search(request('query'))
            ->paginate(5);

        $categories = Category::orderBy('name')->get();

        $requestSort = request('sort', 'created_at');
        $requestOrder = request('order', 'desc');

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
