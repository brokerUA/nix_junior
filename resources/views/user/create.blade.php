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

                <form method="post" action="{{ route('users.store') }}">
                    @csrf
                    <fieldset>
                        <legend>Create user</legend>

                        <div class="mb-3">
                            <label for="inputName" class="form-label">Name</label>
                            <input name="name" type="text" class="form-control" id="inputName"
                                   value="{{ old('name') }}">
                        </div>

                        <div class="mb-3">
                            <label for="inputEmail" class="form-label">Email address</label>
                            <input name="email" type="email" class="form-control" id="inputEmail"
                                   value="{{ old('email') }}">
                        </div>

                        <div class="mb-3">
                            <label for="inputPassword" class="form-label">Password</label>
                            <input name="password" type="password" min="8"
                                   class="form-control" id="inputPassword">
                        </div>

                        <div class="mb-3">
                            <label for="inputPasswordRepeat" class="form-label">Password repeat</label>
                            <input name="password_repeat" type="password" min="8"
                                   class="form-control" id="inputPasswordRepeat">
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>

                    </fieldset>
                </form>

            </div>
        </div>
    </div>
@endsection
