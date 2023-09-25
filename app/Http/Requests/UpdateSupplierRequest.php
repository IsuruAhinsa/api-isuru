<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSupplierRequest extends FormRequest
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
            'province' => 'required',
            'district' => 'required',
            'city' => 'required',
            'company' => 'required|string|max:200',
            'email' => 'required|string|unique:users,email,' . $this->id,
            'phone' => 'required|string|unique:users,phone,' . $this->id,
            'description' => 'required|string',
            'logo' => 'required',
            'status' => 'required|boolean',
            'address' => 'required|string',
            'landmark' => 'nullable|string',
        ];
    }
}
