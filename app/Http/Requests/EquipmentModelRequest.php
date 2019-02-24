<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class EquipmentModelRequest extends FormRequest
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
            'name' => 'string|between:1,191',
            'description' => 'nullable|text|max:600',
            'type_id' => 'integer|exists:equipment_types,id',
            'manufacturer_id' => 'integer|exists:equipment_manufacturers,id',
        ];

        if ($request->method === 'POST') {
            $rules['name'] = 'required|' . $rules['name'];
            $rules['type_id'] = 'required|' . $rules['type_id'];
            $rules['manufacturer_id'] = 'required|' . $rules['manufacturer_id'];
        }

        return $rules;
    }
}