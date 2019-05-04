<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use App\Request as RequestModel;
use Illuminate\Foundation\Http\FormRequest;

class RequestRequest extends FormRequest
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
        if ($method === Request::METHOD_GET && $request->route()->getName() === 'requests.index') {
            return [
                'search' => 'string',
                'columns' => 'array',
                'columns.*' => 'string|in:' . join(',', RequestModel::ALLOW_COLUMNS_SEARCH),
                'sortColumn' => 'string|in:' . join(',', RequestModel::ALLOW_COLUMNS_SORT),
                'sortOrder' => 'string|in:ascending,descending',
            ];
        }

        return [
            //
        ];
    }
}
