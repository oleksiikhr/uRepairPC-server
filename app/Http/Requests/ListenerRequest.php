<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class ListenerRequest extends FormRequest
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
            'rooms' => 'array',
            'rooms.*' => 'required|string',
        ];

        if ($request->route()->getName() === 'roles.index') {
            $rules['rooms'] = 'required|'.$rules['rooms'];
        }

        return $rules;
    }
}
