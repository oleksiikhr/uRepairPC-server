<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'first_name' => 'string|between:1,191',
            'middle_name' => 'nullable|string|max:191',
            'last_name' => 'string|between:1,191',
            'role' => 'string|in:' . implode(',', User::ROLES),
            'phone' => 'nullable|string|max:191',
            'description' => 'nullable|string|max:600',
        ];

        if ($request->method === 'POST') {
            $rules['email'] = 'required|email|unique:users,email';
            $rules['first_name'] = 'required|' . $rules['first_name'];
            $rules['last_name'] = 'required|' . $rules['last_name'];

            if (Auth::user()->admin()) {
                $rules['role'] = 'required|' . $rules['role'];
            }
        }

        return $rules;
    }
}
