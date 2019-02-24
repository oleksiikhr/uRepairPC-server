<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class EquipmentTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @param  Request  $request
     * @return array
     */
    public function rules(Request $request)
    {
        $rules = [
            'name' => 'string|between:1,191|unique:equipment_types,name',
            'description' => 'nullable|text|max:600',
        ];

        if ($request->method === 'POST') {
            $rules['name'] = 'required|' . $rules['name'];
        }

        return $rules;
    }
}