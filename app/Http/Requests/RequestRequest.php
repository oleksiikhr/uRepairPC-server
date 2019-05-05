<?php

namespace App\Http\Requests;

use App\Enums\Permissions;
use Illuminate\Http\Request;
use App\Request as RequestModel;
use Illuminate\Support\Facades\Auth;
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
        $me = Auth::user();

        // List of all users
        if ($request->route()->getName() === 'requests.index') {
            return [
                'search' => 'string',
                'columns' => 'array',
                'columns.*' => 'string|in:' . join(',', RequestModel::ALLOW_COLUMNS_SEARCH),
                'sortColumn' => 'string|in:' . join(',', RequestModel::ALLOW_COLUMNS_SORT),
                'sortOrder' => 'string|in:ascending,descending',
                'priority_id' => 'integer|min:1',
                'status_id' => 'integer|min:1',
                'type_id' => 'integer|min:1',
            ];
        }

        $rules = [
            'title' => 'string|between:1,191',
            'location' => 'string|between:1,191',
            'description' => 'nullable|string|max:1200',
        ];

        if ($me->can(Permissions::EQUIPMENTS_VIEW)) {
            $rules['equipment_id'] = 'integer|exists:equipments,id,deleted_at,NULL';
        }

        // Store
        if ($request->method === Request::METHOD_POST) {
            $rules['title'] = 'required|' . $rules['title'];
        }

        return $rules;
    }
}
