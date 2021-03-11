<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexUserRequest extends FormRequest
{
    protected $redirectRoute = 'users.index';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'page' => 'nullable|integer|min:1',
            'query' => 'nullable|string|max:100',
            'sort' => 'nullable|string',
            'order' => ['nullable', Rule::in(['asc', 'desc'])],
            'filter' => 'nullable|array',
            'filter.id' => 'nullable|string',
            'filter.name' => 'nullable|string',
            'filter.email' => 'nullable|string',
        ];
    }
}
