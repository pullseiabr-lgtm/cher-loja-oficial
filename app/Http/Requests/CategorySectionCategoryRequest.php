<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategorySectionCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_category_id' => ['required', 'integer', 'exists:product_categories,id'],
        ];
    }
}
