<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
        $method = $request->method;

        // List of all users
        if ($method === Request::METHOD_GET && $request->route()->getName() === 'roles.index') {
            return [
                'search' => 'string',
                'columns' => 'array',
                'columns.*' => 'string|in:name,display_name,guard_name',
                'sortColumn' => 'string|in:name,display_name,guard_name',
                'sortOrder' => 'string|in:ascending,descending',
                'permissions' => 'boolean',
                'count' => 'int',
            ];
        }

        return [];
    }
}
