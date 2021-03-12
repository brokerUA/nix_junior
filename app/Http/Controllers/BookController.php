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
            'query' => 'nullable|string|max:100',
            'sort' => 'nullable|string',
            'order' => ['nullable', Rule::in(['asc', 'desc'])],
            'filter' => 'nullable|array',
            'filter.title' => 'nullable|string',
            'filter.description' => 'nullable|string',
            'filter.author_id' => 'nullable|string',
            'filter.category_id' => 'nullable|integer|min:1',
            'filter.created_at' => 'nullable|date',
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
