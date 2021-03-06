@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">

                @if (session('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="get" action="{{ route('books.index') }}">

                    <div class="row mb-4">
                        <div class="col-6">
                            <div class="input-group">
                                <input type="search" name="query" class="form-control"
                                       value="{{ old('query') ?? request('query') }}"
                                       placeholder="search" maxlength="100">
                                <button class="btn btn-outline-secondary" type="submit">Find</button>
                            </div>
                        </div>
                        <div class="col-6 text-right">
                            @role('admin')
                            <a href="{{ route('books.create') }}" class="btn btn-primary">Add book</a>
                            @endrole
                        </div>
                    </div>

                    <input type="hidden" name="sort" value="{{ request('sort', 'created_at') }}">
                    <input type="hidden" name="order" value="{{ request('order', 'desc') }}">

                    <table class="table">
                        <thead>
                        <tr>
                            <th>
                                <x-table.sortable-field title="Title" field="title"/>
                            </th>
                            <th>
                                Description
                            </th>
                            <th>
                                <x-table.sortable-field title="Author" field="author_id"/>
                            </th>
                            <th>
                                <x-table.sortable-field title="Category" field="category_id"/>
                            </th>
                            <th>
                                <x-table.sortable-field title="Added" field="created_at"/>
                            </th>
                            <th></th>
                        </tr>
                        <tr>
                            <th>
                                <input type="text" name="filter[title]" placeholder="filter by title"
                                       class="form-control"
                                       value="{{ old('filter.title') ?? request('filter.title') }}">
                            </th>
                            <th>
                                <input type="text" name="filter[description]" placeholder="filter by description"
                                       class="form-control"
                                       value="{{ old('filter.description') ?? request('filter.description') }}">
                            </th>
                            <th>
                                <input type="text" name="filter[author_id]" placeholder="filter by author"
                                       class="form-control"
                                       value="{{ old('filter.author_id') ?? request('filter.author_id') }}">
                            </th>
                            <th>
                                <select name="filter[category_id]" class="form-control">
                                    <option value="" selected>???</option>
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
                                       class="form-control"
                                       value="{{ old('filter.created_at') ?? request('filter.created_at') }}">
                            </th>
                            <th>
                                <button class="btn btn-secondary" type="submit">Filter</button>
                            </th>
                        </tr>
                        </form>
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
                                    <td>
                                        @role('admin')
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('books.edit', $book->id) }}" class="btn btn-warning">Edit</a>
                                            <form method="post" action="{{ route('books.destroy', $book->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button onclick="return confirm('Really delete this book?')"
                                                        class="btn btn-danger">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                        @endrole
                                    </td>
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

                <div class="col-auto">
                    {{ $books->withQueryString()->links() }}
                </div>

            </div>
        </div>
    </div>
@endsection
