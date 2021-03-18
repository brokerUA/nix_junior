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

                <form method="post" action="{{ route('books.update', $book->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <fieldset>
                        <legend>Update book</legend>

                        <div class="mb-3">
                            <label for="InputTitle" class="form-label">Title</label>
                            <input type="text" maxlength="255" class="form-control" id="InputTitle"
                                   name="title" value="{{ old('title') ?? $book->title }}">
                        </div>

                        <div class="mb-3">
                            <label for="InputAuthor" class="form-label">Author</label>
                            <input type="text" class="form-control" id="InputAuthor" list="datalistAuthors"
                                   name="author" value="{{ old('author') ?? $book->author->name }}">
                            <datalist id="datalistAuthors">
                                @foreach($authors as $author)
                                    <option value="{{ $author->name }}"></option>
                                @endforeach
                            </datalist>
                        </div>

                        <div class="mb-3">
                            <label for="InputDescription" class="form-label">Description</label>
                            <input type="text" maxlength="255" class="form-control" id="InputDescription"
                                   name="description" value="{{ old('description') ?? $book->description }}">
                        </div>

                        <div class="mb-3">
                            <label for="InputCategory" class="form-label">Category</label>
                            <select name="category_id" class="form-control" id="InputCategory">
                                @foreach($categories as $category)
                                    <option
                                        {{ (old('category_id') ?? $book->category_id) == $category->id ? 'selected' : '' }}
                                        value="{{ $category->id }}">
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        @if($book->poster)
                            <div class="row mb-3">
                                <div class="col-3">
                                    <img class="img-fluid"
                                         src="{{ asset($book->poster) }}"
                                         alt="{{ $book->title }}">
                                </div>
                                <div class="col-9">
                                    <label for="InputPoster" class="form-label">Poster</label>
                                    <input type="file" class="form-control" id="InputPoster"
                                           name="poster">
                                </div>
                            </div>
                        @else
                            <div class="mb-3">
                                <label for="InputPoster" class="form-label">Poster</label>
                                <input type="file" class="form-control" id="InputPoster"
                                       name="poster">
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-6">
                                <a class="btn btn-secondary" href="{{ url()->previous() }}">Back</a>
                            </div>
                            <div class="col-6 text-right">
                                <button type="submit" class="btn btn-success">Update</button>
                            </div>
                        </div>

                    </fieldset>
                </form>

            </div>
        </div>
    </div>
@endsection
