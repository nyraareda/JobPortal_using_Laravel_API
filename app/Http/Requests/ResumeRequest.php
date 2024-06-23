<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResumeRequest extends FormRequest
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
            'filename' => ['required','mimes:pdf,doc,docx'],
        ];

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['user_id'] = ['sometimes', 'nullable', 'exists:users,id'];
            $rules['filename'] = ['sometimes', 'nullable','mimes:pdf,doc,docx'];
        }

        return $rules;
}
}
