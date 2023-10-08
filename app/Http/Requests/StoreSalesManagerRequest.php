<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreSalesManagerRequest extends FormRequest
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
            'shop' => 'required|numeric',
            'name' => 'required|string|min:3|max:200',
            'email' => 'required|string|unique:sales_managers,email',
            'phone' => 'required|string|unique:sales_managers,phone',
            'nic' => 'required|string|unique:sales_managers,nic',
            'bio' => 'required|string',
            'photo' => 'required',
            'nic_photo' => 'required',
            'status' => 'required',
            'address' => 'required|string|min:3|max:200',
            'landmark' => 'nullable|string|max:200',
            'password' => [Password::required(), 'confirmed', Password::min(8)->mixedCase()->uncompromised()]
        ];
    }
}
