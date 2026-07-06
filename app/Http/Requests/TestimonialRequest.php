<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TestimonialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'       => ['required', 'string', 'max:190'],
            'content'    => ['required', 'string', 'max:2000'],
            'rating'     => ['required', 'integer', 'min:1', 'max:5'],
            'status'     => ['required', 'numeric'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'image'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
        ];
    }
}
