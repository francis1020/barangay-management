<?php

namespace App\Http\Requests;

class UpdateResidentRequest extends StoreResidentRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'profile_photo' => ['nullable', 'image', 'max:2048'],
        ]);
    }
}
