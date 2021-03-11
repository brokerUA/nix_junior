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

                <div class="mb-4">
                    <a href="{{ route('users.create') }}" class="btn btn-outline-secondary">Create user</a>
                </div>

                <form method="get" action="{{ route('users.index') }}">

                    <fieldset>
                        <legend>Search</legend>
                        <input type="search" name="query"
                               value="{{ request('query') ?? old('query') }}"
                               placeholder="fulltext search" maxlength="100">
                        <button type="submit">Find</button>
                    </fieldset>

                    <table class="table">
                        <thead>
                        <tr>
                            <th>
                                <a href="{{ update_query(['sort' => 'id', 'order' => ($requestSort == 'id' && $requestOrder == 'desc') ? 'asc' : 'desc'], ['page']) }}">
                                    ID
                                    @if ($requestSort == 'id')
                                        @if ($requestOrder == 'asc') &darr; @else &uarr; @endif
                                    @else &#x21C5; @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ update_query(['sort' => 'name', 'order' => ($requestSort == 'name' && $requestOrder == 'desc') ? 'asc' : 'desc'], ['page']) }}">
                                    Name
                                    @if ($requestSort == 'name')
                                        @if ($requestOrder == 'asc') &darr; @else &uarr; @endif
                                    @else &#x21C5; @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ update_query(['sort' => 'email', 'order' => ($requestSort == 'email' && $requestOrder == 'desc') ? 'asc' : 'desc'], ['page']) }}">
                                    Email
                                    @if ($requestSort == 'email')
                                        @if ($requestOrder == 'asc') &darr; @else &uarr; @endif
                                    @else &#x21C5; @endif
                                </a>
                            </th>
                            <th></th>
                        </tr>
                        <tr>
                            <th>
                                <input type="number" name="filter[id]" placeholder="filter by id"
                                       value="{{ request('filter.id') ?? old('filter.id') }}">
                            </th>
                            <th>
                                <input type="text" name="filter[name]" placeholder="filter by name"
                                       value="{{ request('filter.name') ?? old('filter.name') }}">
                            </th>
                            <th>
                                <input type="text" name="filter[email]" placeholder="filter by email"
                                       value="{{ request('filter.email') ?? old('filter.email') }}">
                            </th>
                            <th>
                                <button type="submit">apply</button>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @if (isset($users) && count($users))
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <a class="btn btn-warning" href="{{ route('users.edit', $user->id) }}">
                                            edit
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6">
                                    Users is not found.
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>

                </form>
                {{ $users->withQueryString()->links() }}

            </div>
        </div>
    </div>
@endsection
