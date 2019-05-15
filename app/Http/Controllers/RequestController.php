<?php

namespace App\Http\Controllers;

use App\User;
use App\RequestType;
use App\RequestStatus;
use App\RequestPriority;
use App\Enums\Permissions;
use Illuminate\Http\Request;
use App\Request as RequestModel;
use App\Http\Helpers\FileHelper;
use App\Events\Requests\EDelete;
use App\Events\Requests\EUpdate;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RequestRequest;

class RequestController extends Controller
{
    /**
     * @var RequestModel
     */
    private $_requestModel;

    /**
     * @var User
     */
    private $_currentUser;

    /**
     * Add middleware depends on user permissions.
     *
     * @param  Request  $request
     * @return array
     */
    public function permissions(Request $request): array
    {
        if (! Auth::check()) {
            $this->middleware('jwt.auth');
            return [];
        }

        $permissions = [
            'show' => Permissions::REQUESTS_VIEW,
            'store' => Permissions::REQUESTS_CREATE,
            'update' => Permissions::REQUESTS_EDIT,
            'destroy' => Permissions::REQUESTS_DELETE,
        ];

        $requestId = (int)$request->route('request');
        $this->_currentUser = Auth::user();

        if ($requestId) {
            $this->_requestModel = RequestModel::findOrFail($requestId);

            // If user created this request
            if ($this->_requestModel->user_id === $this->_currentUser->id) {
                unset($permissions['show']);
            }

            // If user assign to request, disable some permissions for access
            if ($this->_requestModel->assign_id === $this->_currentUser->id) {
                unset($permissions['show']);
                unset($permissions['update']);
            }
        }

        return $permissions;
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

        // Without REQUESTS_VIEW permission - can see only own requests
        if (! $this->_currentUser->can(Permissions::REQUESTS_VIEW)) {
            $query->where('user_id', $this->_currentUser->id);
        }

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
        $requestModel->user_id = $this->_currentUser->id;
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        $request = RequestModel::querySelectJoins()->findOrFail($id);

        return response()->json([
            'message' => __('app.requests.show'),
            'request' => $request,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  RequestRequest  $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(RequestRequest $request, int $id)
    {
        $this->_requestModel->fill($request->all());
        $canSeeConfig = $this->_currentUser->can(Permissions::REQUESTS_CONFIG_VIEW);

        // Only user, who can edit every request - can assign user to request
        if ($request->has('assign_id') && $this->_currentUser->can(Permissions::REQUESTS_EDIT)) {
            $this->_requestModel->assign_id = $request->assign_id;
        }
        if ($request->has('type_id') && $canSeeConfig) {
            $this->_requestModel->type_id = $request->type_id;
        }
        if ($request->has('priority_id') && $canSeeConfig) {
            $this->_requestModel->priority_id = $request->priority_id;
        }
        if ($request->has('status_id') && $canSeeConfig) {
            $this->_requestModel->status_id = $request->status_id;
        }

        if (! $this->_requestModel->save()) {
            return response()->json(['message' => __('app.database.save_error')], 422);
        }

        $request = RequestModel::querySelectJoins()->findOrFail($id);
        event(new EUpdate($id, $request->toArray()));

        return response()->json([
            'message' => __('app.requests.update'),
            'request' => $request,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  RequestRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(RequestRequest $request, int $id)
    {
        // Destroy files
        if ($request->file_delete) {
            // TODO
        }

        // Destroy comments

        if (! $this->_requestModel->delete()) {
            return response()->json(['message' => __('app.database.destroy_error')], 422);
        }

        event(new EDelete($id));

        return response()->json([
            'message' => __('app.requests.destroy'),
        ]);
    }
}
