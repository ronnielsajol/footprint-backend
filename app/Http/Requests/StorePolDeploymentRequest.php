<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePolDeploymentRequest extends FormRequest
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
            'event_name' => ['required', 'string', 'max:255'],
            'exact_venue' => ['required', 'string', 'max:255'],
            'lgu' => ['nullable', 'string', 'max:255'],
            'barangay' => ['nullable', 'string', 'max:255'],
            'region' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'province' => ['nullable', 'string', 'max:255'],
            'deployment_month' => ['required', 'integer', 'min:1', 'max:12'],
            'deployment_year' => ['required', 'integer', 'min:2020', 'max:2100'],
            'turnover_date' => ['nullable', 'date'],
            'pol_officer' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'asc_type' => ['nullable', 'in:virtual,actual'],
            'llc' => ['nullable', 'string', 'max:255'],
            'psc' => ['nullable', 'string', 'max:255'],
            'proponent' => ['nullable', 'string', 'max:255'],
            'sector_recipient' => ['nullable', 'string', 'max:255'],
            'count' => ['nullable', 'integer', 'min:0'],
            'unit' => ['nullable', 'string', 'max:255'],
            'donation_summary' => ['nullable', 'string'],
            'amount' => ['nullable', 'numeric', 'min:0'],
            'source' => ['nullable', 'in:TESDA,DSWD-AICS,DOLE-DILP,DOLE-TUPAD,DOH-MAIFIP,Private'],
            'remarks' => ['nullable', 'string'],
        ];
    }
}
