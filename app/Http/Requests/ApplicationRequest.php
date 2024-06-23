<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationRequest extends FormRequest
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
            'job_id' => ['required', 'exists:jobs,id'],
            'user_id' => ['required', 'exists:users,id'],
            'resume_id' => ['required','exists:resumes,id'],
            'cover_letter' => ['required', 'string','min:5'],
            'status' => ['required','in:pending, accepted, rejected'],
        ];

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) 
        {
            $rules['job_id'] = ['sometimes', 'nullable', 'exists:jobs,id'];
            $rules['user_id'] = ['sometimes', 'nullable','exists:users,id'];
            $rules['resume_id'] = ['sometimes', 'nullable','exists:resumes,id'];
            $rules['cover_letter'] = ['sometimes', 'nullable','string','min:5'];
            $rules['status'] = ['sometimes', 'nullable','in:pending, accepted, rejected'];
        }

        return $rules;
    }
}
