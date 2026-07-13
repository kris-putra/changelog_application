<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'url' => ['required', 'string', 'max:255', Rule::unique('applications', 'url')->ignore($this->route('application')), 'regex:/^[a-zA-Z0-9]([a-zA-Z0-9\-]*\.)+[a-zA-Z]{2,}$/'],
            'location' => ['required', 'string', 'in:Local Server,PDNS Server,Third Party'],
        ];
    }
}

