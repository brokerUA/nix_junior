@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="get" action="{{ route('book.index') }}">

                    <fieldset>
                        <legend>Search</legend>
                        <input type="search" name="query"
                               value="{{ request('action') == 'search' ? request('query') : old('query') }}"
                               placeholder="fulltext search" maxlength="100">
                        <button type="submit" name="action" value="search">Find</button>
                    </fieldset>

                    <table class="table">
                        <thead>
                        <tr>
                            <th>
                                <a href="{{ update_query(['sort' => 'title', 'order' => ($requestSort == 'title' && $requestOrder == 'desc') ? 'asc' : 'desc'], ['page']) }}" style="text-decoration: none;">
                                    Title
                                    @if ($requestSort == 'title')
                                        @if ($requestOrder == 'asc')
                                            &darr;
                                        @else
                                            &uarr;
                                        @endif
                                    @else
                                        &#x21C5;
                                    @endif
                                </a>
                            </th>
                            <th>Description</th>
                            <th>
                                <a href="{{ update_query(['sort' => 'author', 'order' => ($requestSort == 'author' && $requestOrder == 'desc') ? 'asc' : 'desc'], ['page']) }}" style="text-decoration: none;">
                                    Author
                                    @if ($requestSort == 'author')
                                        @if ($requestOrder == 'asc')
                                            &darr;
                                        @else
                                            &uarr;
                                        @endif
                                    @else
                                        &#x21C5;
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ update_query(['sort' => 'category', 'order' => ($requestSort == 'category' && $requestOrder == 'desc') ? 'asc' : 'desc'], ['page']) }}" style="text-decoration: none;">
                                    Category
                                    @if ($requestSort == 'category')
                                        @if ($requestOrder == 'asc')
                                            &darr;
                                        @else
                                            &uarr;
                                        @endif
                                    @else
                                        &#x21C5;
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ update_query(['sort' => 'created_at', 'order' => ($requestSort == 'created_at' && $requestOrder == 'desc') ? 'asc' : 'desc'], ['page']) }}" style="text-decoration: none;">
                                    Added
                                    @if ($requestSort == 'created_at')
                                        @if ($requestOrder == 'asc')
                                            &darr;
                                        @else
                                            &uarr;
                                        @endif
                                    @else
                                        &#x21C5;
                                    @endif
                                </a>
                            </th>
                            <th></th>
                        </tr>
                        <tr>
                            <th>
                                <input type="text" name="filter[title]" placeholder="filter by title"
                                       value="{{ request('action') == 'filter' ? request('filter.title') : old('filter.title') }}">
                            </th>
                            <th>
                                <input type="text" name="filter[description]" placeholder="filter by description"
                                       value="{{ request('action') == 'filter' ? request('filter.description') : old('filter.description') }}">
                            </th>
                            <th>
                                <input type="text" name="filter[author]" placeholder="filter by author"
                                       value="{{ request('action') == 'filter' ? request('filter.author') : old('filter.author') }}">
                            </th>
                            <th>
                                <select name="filter[category]">
                                    <option value="" selected>—</option>
                                    @foreach($categories as $category)
                                        <option
                                            {{ (request('action') == 'filter' && request('filter.category') == $category->id) ? 'selected' : old('filter.category') }}
                                            value="{{ $category->id }}">
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </th>
                            <th>
                                <input type="date" name="filter[date]" placeholder="filter by date"
                                       value="{{ request('action') == 'filter' ? request('filter.date') : old('filter.date') }}">
                            </th>
                            <th>
                                <button type="submit" name="action" value="filter">apply</button>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @if (isset($books) && count($books))
                            @foreach($books as $book)
                                <tr>
                                    <td>{{ $book->title }}</td>
                                    <td>{{ $book->description }}</td>
                                    <td>{{ $book->author->name }}</td>
                                    <td>{{ $book->category->name }}</td>
                                    <td>{{ $book->created_at }}</td>
                                    <td></td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6">
                                    Books is not found.
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>

                </form>
                {{ $books->withQueryString()->links() }}

            </div>
        </div>
    </div>
@endsection
