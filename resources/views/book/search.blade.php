@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">

                @if (isset($books) && count($books))

                    <form method="get" action="{{ route('book.search') }}">

                        <fieldset style="margin-bottom: 1rem; display: inline-block">
                            <legend>Search</legend>
                            <input type="search" name="query" value="{{ request('search') }}" placeholder="fulltext search">
                            <button type="submit">Find</button>
                        </fieldset>

                        <fieldset style="margin-bottom: 1rem; display: inline-block">
                            <legend>Filter</legend>
                            <a href="{{ route('book.index') }}">filter</a>
                        </fieldset>

                        <table class="table">
                            <tr>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Author</th>
                                <th>Category</th>
                                <th>Added</th>
                            </tr>
                            @foreach($books as $book)
                                <tr>
                                    <td>{{ $book->title }}</td>
                                    <td>{{ $book->description }}</td>
                                    <td>{{ $book->author->name }}</td>
                                    <td>{{ $book->category->name }}</td>
                                    <td>{{ $book->created_at }}</td>
                                </tr>
                            @endforeach
                        </table>

                    </form>
                    {{ $books->links() }}
                @else
                    <p>Books is not found.</p>
                @endif

            </div>
        </div>
    </div>
@endsection
