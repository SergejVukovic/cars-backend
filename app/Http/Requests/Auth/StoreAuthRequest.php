<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class StoreAuthRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => ['required','email','unique:users,email'],
            'phone_number' => ['string'],
            'country' => ['string'],
            'city' => ['required'],
            'address' => ['string'],
            'postcode' => ['string'],
            'password' => ['required', 'string']
        ];
    }
}
