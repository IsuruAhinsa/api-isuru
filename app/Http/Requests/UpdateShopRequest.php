<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateShopRequest extends FormRequest
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
            'province' => 'required|numeric',
            'district' => 'required|numeric',
            'city' => 'required|numeric',
            'company' => 'required|string|min:3|max:200',
            'email' => 'required|string|unique:shops,email,' . $this->id,
            'phone' => 'required|string|unique:shops,phone,' . $this->id,
            'description' => 'required|string',
            'logo' => 'nullable',
            'status' => 'required',
            'address' => 'required|string|min:3|max:200',
            'landmark' => 'nullable|string|max:200',
        ];
    }
}
