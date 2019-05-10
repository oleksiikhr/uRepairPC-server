<?php

namespace App\Http\Controllers;

use App\RequestType;
use App\RequestStatus;
use App\RequestPriority;
use App\Enums\Permissions;
use Illuminate\Http\Request;
use App\Request as RequestModel;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RequestRequest;

class RequestController extends Controller
{
    /**
     * @var RequestModel
     */
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
     * @return \Illuminate\Http\JsonResponse
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

        // Filters
        if ($request->priority_id) {
            $query->where('requests.priority_id', $request->priority_id);
        }
        if ($request->status_id) {
            $query->where('requests.status_id', $request->status_id);
        }
        if ($request->type_id) {
            $query->where('requests.type_id', $request->type_id);
        }

        $list = $query->paginate(self::PAGINATE_DEFAULT);

        return response()->json($list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  RequestRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(RequestRequest $request)
    {
        $requestModel = new RequestModel;
        $requestModel->user_id = Auth::id();
        $requestModel->type_id = RequestType::getDefaultValue()->id;
        $requestModel->priority_id = RequestPriority::getDefaultValue()->id;
        $requestModel->status_id = RequestStatus::getDefaultValue()->id;
        $requestModel->fill($request->all());

        if (! $requestModel->save()) {
            return response()->json(['message' => __('app.database.save_error')], 422);
        }

        return response()->json([
            'message' => __('app.requests.store'),
            'request' => $requestModel,
        ]);
    }
}
