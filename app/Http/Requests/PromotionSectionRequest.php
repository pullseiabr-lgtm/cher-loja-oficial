<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PromotionSectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => [
                'required',
                'string',
                'max:190',
                Rule::unique('promotion_sections', 'name')->ignore($this->route('promotionSection.id'))
            ],
            'layout_type' => ['required', 'string', Rule::in(['grid', 'carousel'])],
            'columns'     => ['nullable', 'integer', 'min:1', 'max:6'],
            'status'      => ['required', 'numeric'],
        ];
    }
}
