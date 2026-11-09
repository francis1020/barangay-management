<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreResidentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'purok_id'               => ['required', 'exists:puroks,id'],
            'household_id'           => ['nullable', 'exists:households,id'],
            'first_name'             => ['required', 'string', 'max:100'],
            'middle_name'            => ['nullable', 'string', 'max:100'],
            'last_name'              => ['required', 'string', 'max:100'],
            'suffix'                 => ['nullable', 'string', 'max:20'],
            'birthdate'              => ['required', 'date', 'before:today'],
            'birthplace'             => ['required', 'string', 'max:255'],
            'sex'                    => ['required', 'in:male,female,other'],
            'civil_status'           => ['required', 'in:single,married,widowed,separated,annulled'],
            'citizenship'            => ['required', 'string', 'max:100'],
            'religion'               => ['nullable', 'string', 'max:100'],
            'occupation'             => ['nullable', 'string', 'max:150'],
            'employment_status'      => ['nullable', 'in:employed,self_employed,unemployed,student,retired'],
            'educational_attainment' => ['nullable', 'string', 'max:100'],
            'contact_number'         => ['nullable', 'string', 'regex:/^(?:\+639|09)\d{9}$/', 'max:20'],
            'email'                  => ['nullable', 'email', 'max:255'],
            'philhealth_no'          => ['nullable', 'string', 'max:50'],
            'sss_no'                 => ['nullable', 'string', 'max:50'],
            'tin_no'                 => ['nullable', 'string', 'max:50'],
            'status'                 => ['required', 'in:active,inactive,deceased,transferred'],
            'is_4ps_beneficiary'     => ['boolean'],
            'is_pwd'                 => ['boolean'],
            'is_solo_parent'         => ['boolean'],
            'profile_photo'          => ['nullable', 'image', 'max:2048'],
            // Documents
            'documents'              => ['nullable', 'array'],
            'documents.*'            => ['file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'document_types'         => ['nullable', 'array'],
            'document_types.*'       => ['string'],
            // Voter info
            'is_registered'          => ['boolean'],
            'precinct_no'            => ['required_if:is_registered,true', 'nullable', 'string', 'max:50'],
            'voters_id_no'           => ['required_if:is_registered,true', 'nullable', 'string', 'max:50'],
            'registration_date'      => ['nullable', 'date'],
            'voting_center'          => ['nullable', 'string', 'max:255'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_4ps_beneficiary' => $this->boolean('is_4ps_beneficiary'),
            'is_pwd'             => $this->boolean('is_pwd'),
            'is_solo_parent'     => $this->boolean('is_solo_parent'),
            'is_registered'      => $this->boolean('is_registered'),
        ]);
    }
}
