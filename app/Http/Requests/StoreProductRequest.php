<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'brand_id' => 'numeric',
            'category' => 'required|numeric',
            'country_id' => 'numeric',
            'supplier_id' => 'numeric',
            'sub_category_id' => 'numeric',
            'name' => 'required|string|min:3|max:255',
            'slug' => 'required|string|min:3|max:255|unique:products',
            'status' => 'required|numeric',
            'cost' => 'required|numeric',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'discount' => 'numeric',
            'discount_fixed' => 'numeric',
            'discount_start' => 'date',
            'discount_end' => 'date',
            'sku' => 'required|string|min:3|max:255|unique:products',
            'description' => 'required|max:1000|min:10',
            'attributes' => 'array',
            'specifications' => 'array',
        ];
    }
}
