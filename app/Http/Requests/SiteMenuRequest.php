<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SiteMenuRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:191',
            'location' => 'required|in:header,footer',
            'status'   => 'nullable|numeric',
        ];
    }
}
