<?php

namespace App\Http\Services;

use App\Http\Requests\IndexUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Traits\ServiceTrait;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserService
 * @package App\Http\Services
 */
class UserService
{
    use ServiceTrait;

    /**
     * @var User
     */
    private $model;

    /**
     * @param Builder $query
     * @param string $sort
     * @param string $order
     * @return Builder
     */
    protected function scopeSort(Builder $query, string $sort, string $order): Builder
    {
        return $query->orderBy($sort, $order);
    }

    /**
     * @param Builder $query
     * @param string|null $searchQuery
     * @param array $filters
     * @return Builder
     */
    protected function scopeSearch(Builder $query, ?string $searchQuery, array $filters): Builder
    {
        if (is_null($searchQuery)) {
            return $query;
        }

        return $query->where(function ($query) use (&$filters, &$searchQuery) {

            $fields = array_diff(
                $this->model->searchableFields,
                array_keys( array_filter($filters) )
            );

            foreach ($fields as $key => $fieldName) {
                $methodName = $key ? 'orWhere' : 'where';
                $query = $query->$methodName($fieldName, 'like', '%' . $searchQuery . '%');
            }

            return $query;
        });
    }

    /**
     * @param Builder $query
     * @param array $filters
     * @return Builder
     */
    protected function scopeFilter(Builder $query, array $filters): Builder
    {
        $filters = array_filter($filters);

        foreach ($filters as $name => $value) {
            $query = $query->where($name, 'like', '%' . $value . '%');
        }

        return $query;
    }

    /**
     * UserService constructor.
     * @param User $model
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * @param IndexUserRequest $request
     * @param int $paginateCount
     * @return LengthAwarePaginator
     */
    public function getFilteredWithPaginate(IndexUserRequest $request, int $paginateCount): LengthAwarePaginator
    {
        return $this->buildFilteredWithPaginate($request, $paginateCount);
    }

    /**
     * @param StoreUserRequest $request
     * @return User
     */
    public function save(StoreUserRequest $request): User
    {
        $this->model->name = $request->input('name');
        $this->model->email = $request->input('email');
        $this->model->password = Hash::make($request->input('password'));
        $this->model->save();

        return $this->model;
    }

    /**
     * @param UpdateUserRequest $request
     * @param int $id
     * @return User
     */
    public function update(UpdateUserRequest $request, int $id): User
    {
        $user = $this->model->find($id);

        $user->name = $request->input('name');
        $user->email = $request->input('email');

        if ($request->has('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->save();

        return $user;
    }
}
