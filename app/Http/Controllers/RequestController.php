<?php

namespace App\Http\Controllers;

use App\Enums\Permissions;
use Illuminate\Http\Request;
use App\Request as RequestModel;
use App\Http\Requests\RequestRequest;

class RequestController extends Controller
{
    private $_requestModel;

    /**
     * Add middleware depends on user permissions.
     *
     * @param  Request  $request
     * @return array
     */
    public function permissions(Request $request): array
    {
        $requestId = (int)$request->route('request');
        if ($requestId) {
            $this->_requestModel = RequestModel::findOrFail($requestId);
//            TODO user_assign
        }

        return [
            'index' => Permissions::REQUESTS_VIEW,
            'show' => Permissions::REQUESTS_VIEW,
            'store' => Permissions::REQUESTS_CREATE,
            'update' => Permissions::REQUESTS_EDIT,
            'destroy' => Permissions::REQUESTS_DELETE,
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @param  RequestRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function index(RequestRequest $request)
    {
        $query = RequestModel::querySelectJoins();

        // Search
        if ($request->has('search') && $request->has('columns') && ! empty($request->columns)) {
            foreach ($request->columns as $column) {
                $query->orWhere(RequestModel::SEARCH_RELATIONSHIP[$column] ?? $column, 'LIKE', '%' . $request->search . '%');
            }
        }

        // Order
        if ($request->has('sortColumn')) {
            $query->orderBy(
                RequestModel::SORT_RELATIONSHIP[$request->sortColumn] ?? $request->sortColumn,
                $request->sortOrder === 'descending' ? 'desc' : 'asc'
            );
        }

        $list = $query->paginate(self::PAGINATE_DEFAULT);

        return response()->json($list);
    }
}
