<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'category' => 'required',
            'name' => 'required|min:3|max:50|string',
            'slug' => 'required|min:3|max:50|string|unique:sub_categories,slug,' . $this->id,
            'description' => 'nullable|max:250|string',
            'serial' => 'required|numeric',
            'status' => 'required|boolean',
        ];
    }
}
