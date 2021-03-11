<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param IndexUserRequest $request
     * @return mixed
     */
    public function index(IndexUserRequest $request)
    {

        $page = $request->input('page');

        if ($page == 1) {
            return redirect()
                ->route('users.index', $request->except(['page']));
        }

        $model = new User;

        /*
         * Search
         * */

        if ($request->filled('query')) {

            $model = $model->where(function ($query) use (&$model, &$request) {
                foreach ($model->searchableFields as $key => $fieldName) {
                    $methodName = $key ? 'orWhere' : 'where';
                    $query = $query->$methodName($fieldName, 'like', '%' . $request->input('query') . '%');
                }
                return $query;
            });

        }

        /*
        * Sort
        * */

        $requestSort = $request->input('sort', 'id');
        $requestOrder = $request->input('order', 'desc');

        $model = $model->orderBy($requestSort, $requestOrder);


        /*
         * Filter
         * */

        $filters = array_filter($request->input('filter', []));

        foreach ($filters as $name => $value) {
            $model = $model->where($name, 'like', '%' . $value . '%');
        }


        $users = $model->paginate(5);

        return view('user.index', compact(
            'users',
            'requestSort',
            'requestOrder',
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return mixed
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreUserRequest  $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function store(StoreUserRequest $request)
    {
        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return redirect()
            ->route('users.index')
            ->with('message', 'User success created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserRequest  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $isChanged = false;

        foreach ($user->getFillable() as $field) {

            if (
                $request->has($field)
                && $user->$field != $request->input($field)
            ) {

                if ($field == 'password' && $request->input($field) != '') {
                    $user->$field = Hash::make($request->input($field));
                    $isChanged = true;
                }

                if ($field != 'password') {
                    $user->$field = $request->input($field);
                    $isChanged = true;
                }

            }

        }

        if ($isChanged) {
            $user->save();
            $message = 'User success updated.';
        } else {
            $message = 'User didn\'t change.';
        }

        return redirect()
            ->route('users.index')
            ->with('message', $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $users
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $users)
    {
        //
    }
}
