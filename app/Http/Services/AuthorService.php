<?php

namespace App\Http\Services;

use App\Models\Author;
use Illuminate\Database\Eloquent\Collection;

class AuthorService
{
    private $model;

    public function __construct(Author $model)
    {
        $this->model = $model;
    }

    public function getAll(): Collection
    {
        return $this->model::orderBy('name')->get();
    }
}
