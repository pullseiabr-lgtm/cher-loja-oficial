<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SiteMenuItemRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'title'        => 'required|string|max:191',
            'type'         => 'required|in:custom,category,page,categories_all',
            'parent_id'    => 'nullable|integer|exists:site_menu_items,id',
            'reference_id' => 'nullable|integer',
            'url'          => 'nullable|string|max:500',
            'target'       => 'nullable|in:_self,_blank',
            'status'       => 'nullable|numeric',
        ];
    }
}
