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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:feature,change,bug',
            'priority' => 'required|in:low,medium,high,urgent',
        ];
    }
}
