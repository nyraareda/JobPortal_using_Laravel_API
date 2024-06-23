<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobRequest extends FormRequest
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
            'company_id' => ['required', 'exists:companies,id'],
            'title' => ['required', 'string','min:3'],
            'description' => ['required', 'string','min:5'],
            'location' => ['required', 'string','min:5'],
            'salary' => ['required', 'string','min:3'],
            'requirements' => ['required', 'string','min:3'],

        ];

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['comany_id'] = ['sometimes', 'nullable', 'exists:companies,id'];
            $rules['title'] = ['sometimes', 'nullable','string','min:5'];
            $rules['description'] = ['sometimes', 'nullable','string','min:5'];
            $rules['location'] = ['sometimes', 'nullable','string','min:5'];
            $rules['salary'] = ['sometimes', 'nullable','string'];
            $rules['requirements'] = ['sometimes', 'nullable','string','min:5'];
        }

        return $rules;
}
    }

