<?php

namespace App\Http\Services;

use App\Http\Requests\IndexUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * @var User
     */
    private $model;

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
        return $this->model
            ->sort(
                $request->input('sort', 'created_at'),
                $request->input('order', 'desc')
            )
            ->search(
                $request->input('query'),
                $request->input('filter', [])
            )
            ->filter(
                $request->input('filter', [])
            )
            ->paginate(
                $paginateCount
            );
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
