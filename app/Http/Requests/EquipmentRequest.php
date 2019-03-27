<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class EquipmentRequest extends FormRequest
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
            'serial_number' => 'nullable|string|between:1,191',
            'inventory_number' => 'nullable|string|max:600',
            'manufacturer_id' => 'integer|exists:equipment_manufacturers,id',
            'type_id' => 'nullable|integer|exists:equipment_types,id',
            'model_id' => 'nullable|integer|exists:equipment_models,id',
            'description' => 'nullable|string|max:600',
        ];

        if ($request->method === 'POST') {
            $rules['type_id'] = 'required|' . $rules['type_id'];
        }

        return $rules;
    }
}
