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
            'detail_perubahan' => 'nullable|string',
            'pemohon_perubahan' => 'nullable|string|max:255',
            'as_is' => 'nullable|string',
            'to_be' => 'nullable|string',
            'klasifikasi_perubahan' => 'required|in:Normal,Emergency',
            'type' => 'required|in:feature,change,bug',
            'priority' => 'required|in:low,medium,high,urgent',
        ];
    }
}
