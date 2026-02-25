<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
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
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'event_type' => ['sometimes', 'required', 'in:pol_deployment,w_asc_deployment'],
            'description' => ['nullable', 'string'],
            'event_date' => ['sometimes', 'required', 'date'],
            'location' => ['sometimes', 'required', 'string', 'max:255'],
            'status' => ['sometimes', 'in:planned,ongoing,completed,cancelled'],
        ];
    }
}
