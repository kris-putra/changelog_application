<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeatureRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'application_id' => 'required|exists:applications,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'pemohon_perubahan' => 'nullable|string|max:255',
            'as_is' => 'nullable|string',
            'to_be' => 'nullable|string',
            'impact' => 'required|string',

            'type' => 'required|in:feature,change,bug,incident',
            'priority' => 'required|in:low,medium,high,urgent',
        ];
    }
}
