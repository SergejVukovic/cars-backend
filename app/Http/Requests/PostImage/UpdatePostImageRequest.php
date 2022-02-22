<?php

namespace App\Http\Requests\PostImage;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostImageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $post = $this->user()->posts()->findOrFail($this->route('post'));
        return !!$post->images()->findOrFail($this->route('image'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'main' => ['boolean'],
            'index' => ['numeric']
        ];
    }
}
