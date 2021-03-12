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

                    <fieldset class="mb-4">
                        <legend>Search</legend>
                        <input type="search" name="query"
                               value="{{ old('query') ?? request('query') }}"
                               placeholder="search query" maxlength="100">
                        <button type="submit">Find</button>
                    </fieldset>

                    <input type="hidden" name="sort" value="{{ $requestSort }}">
                    <input type="hidden" name="order" value="{{ $requestOrder }}">

                    <table class="table">
                        <thead>
                        <tr>
                            <th>
                                <a href="{{ update_query(['sort' => 'title', 'order' => ($requestSort == 'title' && $requestOrder == 'desc') ? 'asc' : 'desc'], ['page']) }}" style="text-decoration: none;">
                                    Title
                                    @if ($requestSort == 'title')
                                        @if ($requestOrder == 'asc') &darr; @else &uarr; @endif
                                    @else
                                        &#x21C5;
                                    @endif
                                </a>
                            </th>
                            <th>Description</th>
                            <th>
                                <a href="{{ update_query(['sort' => 'author_id', 'order' => ($requestSort == 'author_id' && $requestOrder == 'desc') ? 'asc' : 'desc'], ['page']) }}" style="text-decoration: none;">
                                    Author
                                    @if ($requestSort == 'author_id')
                                        @if ($requestOrder == 'asc') &darr; @else &uarr; @endif
                                    @else
                                        &#x21C5;
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ update_query(['sort' => 'category_id', 'order' => ($requestSort == 'category_id' && $requestOrder == 'desc') ? 'asc' : 'desc'], ['page']) }}" style="text-decoration: none;">
                                    Category
                                    @if ($requestSort == 'category_id')
                                        @if ($requestOrder == 'asc') &darr; @else &uarr; @endif
                                    @else
                                        &#x21C5;
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ update_query(['sort' => 'created_at', 'order' => ($requestSort == 'created_at' && $requestOrder == 'desc') ? 'asc' : 'desc'], ['page']) }}" style="text-decoration: none;">
                                    Added
                                    @if ($requestSort == 'created_at')
                                        @if ($requestOrder == 'asc') &darr; @else &uarr; @endif
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
                                       value="{{ old('filter.title') ?? request('filter.title') }}">
                            </th>
                            <th>
                                <input type="text" name="filter[description]" placeholder="filter by description"
                                       value="{{ old('filter.description') ?? request('filter.description') }}">
                            </th>
                            <th>
                                <input type="text" name="filter[author_id]" placeholder="filter by author"
                                       value="{{ old('filter.author_id') ?? request('filter.author_id') }}">
                            </th>
                            <th>
                                <select name="filter[category_id]">
                                    <option value="" selected>—</option>
                                    @foreach($categories as $category)
                                        <option
                                            {{ (old('filter.category_id') ?? request('filter.category_id')) == $category->id ? 'selected' : '' }}
                                            value="{{ $category->id }}">
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </th>
                            <th>
                                <input type="date" name="filter[created_at]" placeholder="filter by date"
                                       value="{{ old('filter.created_at') ?? request('filter.created_at') }}">
                            </th>
                            <th>
                                <button type="submit">apply</button>
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
