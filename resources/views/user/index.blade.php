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

                <form method="get" action="{{ route('users.index') }}">

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
                            <a href="{{ route('users.create') }}" class="btn btn-outline-secondary">Create user</a>
                        </div>
                    </div>

                    <input type="hidden" name="sort" value="{{ request('sort', 'id') }}">
                    <input type="hidden" name="order" value="{{ request('order', 'desc') }}">

                    <table class="table">
                        <thead>
                        <tr>
                            <th>
                                <x-table.sortable-field title="ID" field="id"/>
                            </th>
                            <th>
                                <x-table.sortable-field title="Name" field="name"/>
                            </th>
                            <th>
                                <x-table.sortable-field title="Email" field="email"/>
                            </th>
                            <th></th>
                        </tr>
                        <tr>
                            <th>
                                <input type="number" name="filter[id]" placeholder="filter by id"
                                       value="{{ old('filter.id') ?? request('filter.id') }}">
                            </th>
                            <th>
                                <input type="text" name="filter[name]" placeholder="filter by name"
                                       value="{{ old('filter.name') ?? request('filter.name') }}">
                            </th>
                            <th>
                                <input type="text" name="filter[email]" placeholder="filter by email"
                                       value="{{ old('filter.email') ?? request('filter.email') }}">
                            </th>
                            <th>
                                <button class="btn btn-secondary" type="submit">Filter</button>
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
