<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'event_type' => ['required', 'in:pol_deployment,w_asc_deployment'],
            'description' => ['nullable', 'string'],
            'event_date' => ['required', 'date'],
            'location' => ['required', 'string', 'max:255'],
            'status' => ['nullable', 'in:planned,ongoing,completed,cancelled'],
        ];
    }
}
