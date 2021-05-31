<?php

namespace App\Http\Services;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryService
{
    private $model;

    public function __construct(Category $model)
    {
        $this->model = $model;
    }

    public function getAll(): Collection
    {
        return $this->model::select(['id', 'name'])->orderBy('name')->get();
    }
}
