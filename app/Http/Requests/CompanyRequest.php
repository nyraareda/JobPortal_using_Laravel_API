<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'user_id' => ['required', 'exists:users,id'],
            'name' => ['required', 'string','min:3'],
            'industry' => ['required', 'string','min:5'],
            'description' => ['required', 'string','min:5'],
            'location' => ['required', 'string','min:3'],
        ];

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['user_id'] = ['sometimes', 'nullable', 'exists:users,id'];
            $rules['description'] = ['sometimes', 'nullable','string','min:5'];
            $rules['industry'] = ['sometimes', 'nullable','string','min:5'];
            $rules['location'] = ['sometimes', 'nullable','string','min:5'];
        }

        return $rules;
}
}
