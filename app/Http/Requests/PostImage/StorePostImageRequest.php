<?php

namespace App\Http\Requests\PostImage;

use Illuminate\Foundation\Http\FormRequest;

class StorePostImageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $user = $this->user();
        return !!$user->posts()->findOrFail($this->route('post'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'images' => ['required', 'array'],
        ];
    }
}
