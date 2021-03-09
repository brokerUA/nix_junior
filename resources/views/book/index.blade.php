<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>NIX PHP Junior</title>
    </head>
    <body>

    @if (Route::has('login'))

            @auth
                <a href="{{ url('/home') }}">Home</a>
            @else
                <a href="{{ route('login') }}">Log in</a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}">Register</a>
                @endif
            @endauth

    @endif

    @if (isset($books) && count($books))

        <form method="get" action="{{ route('book.search') }}">

            <fieldset style="margin-bottom: 1rem; display: inline-block">
                <legend>Search</legend>
                <input type="search" name="search" value="{{ request('search') }}" placeholder="fulltext search">
                <button type="submit" name="action" value="search">Find</button>
            </fieldset>

        </form>

        <form method="get" action="{{ route('book.index') }}">

            <table border="1" cellspacing="0" cellpadding="5">
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
                               value="{{ request('filter.title') }}">
                    </th>
                    <th>
                        <input type="text" name="filter[description]" placeholder="filter by description"
                               value="{{ request('filter.description') }}">
                    </th>
                    <th>
                        <input type="text" name="filter[author]" placeholder="filter by author"
                               value="{{ request('filter.author') }}">
                    </th>
                    <th>
                        <select name="filter[category]">
                            <option value="" selected>—</option>
                            @foreach($categories as $category)
                                <option
                                    {{ ($category->id == request('filter.category')) ? 'selected' : '' }}
                                    value="{{ $category->id }}">
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </th>
                    <th>
                        <input type="date" name="filter[date]" placeholder="filter by date"
                               value="{{ request('filter.date') }}">
                    </th>
                    <th>
                        <button type="submit" value="filter" name="action">apply</button>
                    </th>
                </tr>
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
            </table>

        </form>
        {{ $books->withQueryString()->links() }}
    @else
        <p>Books is not found.</p>
    @endif

    </body>
</html>
