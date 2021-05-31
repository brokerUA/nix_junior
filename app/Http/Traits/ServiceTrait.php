<?php


namespace App\Http\Traits;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;

trait ServiceTrait
{
    public function buildFilteredWithPaginate($request, int $paginateCount): LengthAwarePaginator
    {
        $queryBuilder = $this->model->newQuery();

        $queryBuilder = $this->scopeSort(
            $queryBuilder,
            $request->input('sort', 'created_at'),
            $request->input('order', 'desc')
        );

        $queryBuilder = $this->scopeSearch(
            $queryBuilder,
            $request->input('query'),
            $request->input('filter', [])
        );

        $queryBuilder = $this->scopeFilter(
            $queryBuilder,
            $request->input('filter', [])
        );

        return $queryBuilder->paginate(
            $paginateCount
        );
    }
}
