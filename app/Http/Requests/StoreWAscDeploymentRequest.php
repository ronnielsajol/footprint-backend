<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWAscDeploymentRequest extends FormRequest
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
            'exact_venue' => ['required', 'string', 'max:255'],
            'barangay' => ['nullable', 'string', 'max:255'],
            'city_municipality' => ['nullable', 'string', 'max:255'],
            'region' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'province' => ['nullable', 'string', 'max:255'],
            'deployment_month' => ['required', 'integer', 'min:1', 'max:12'],
            'deployment_year' => ['required', 'integer', 'min:2020', 'max:2100'],
            'exact_date' => ['required', 'date'],
            'event_tagging' => ['nullable', 'string', 'max:255'],
            'has_socials' => ['boolean'],
            'has_sortie' => ['boolean'],
            'asc_attended' => ['boolean'],
            'llc_attended' => ['boolean'],
            'psc_attended' => ['boolean'],
            'pol_activities' => ['nullable', 'array'],
            'pol_activities.*' => ['string', 'max:500'],
            'sector' => ['nullable', 'in:PTK,Kababaihan,MSMEs,Youth,BHW'],
            'remarks' => ['nullable', 'string'],
        ];
    }
}
