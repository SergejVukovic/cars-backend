<?php

namespace App\Http\Requests\PostImage;

use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;

class ViewAnyPostImageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return !!(new Post)->findOrFail($this->route('post'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
