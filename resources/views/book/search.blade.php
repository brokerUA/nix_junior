<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>NIX PHP Junior</title>
    </head>
    <body>

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

            <table border="1" cellspacing="0" cellpadding="5">
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

    </body>
</html>
