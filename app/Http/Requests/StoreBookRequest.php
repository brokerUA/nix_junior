<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
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
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'required|max:255',
            'category_id' => 'required|integer|exists:categories,id',
            'poster' => 'nullable|image|dimensions:min_width=100,min_height=100,max_width=1000,max_height=1000|max:1024',
        ];
    }
}
