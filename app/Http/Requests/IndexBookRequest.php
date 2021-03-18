<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexBookRequest extends FormRequest
{
    protected $redirectRoute = 'book.index';

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
            'filter.title' => 'nullable|string',
            'filter.description' => 'nullable|string',
            'filter.author_id' => 'nullable|string',
            'filter.category_id' => 'nullable|integer|min:1',
            'filter.created_at' => 'nullable|date',
        ];
    }
}
