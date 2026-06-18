<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategorySectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'   => [
                'required',
                'string',
                'max:190',
                Rule::unique('category_sections', 'name')->ignore($this->route('categorySection.id'))
            ],
            'type'           => ['required', 'string', 'in:categories,products,banner'],
            'title_tag'      => ['nullable', 'string', 'in:h1,h2,custom'],
            'title_position' => ['nullable', 'string', 'in:left,center,right'],
            'item_template'  => ['nullable', 'string', 'in:card,circle'],
            'row_layout'     => ['nullable', 'string', 'in:carousel,left,center,justified'],
            'status'         => ['required', 'numeric', 'max:24'],
        ];
    }
}
