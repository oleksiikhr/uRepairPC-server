<?php

namespace App\Http\Controllers;

use App\User;
use App\RequestComment;
use App\Enums\Permissions;
use Illuminate\Http\Request;
use App\Request as RequestModel;
use Illuminate\Support\Facades\Auth;
use App\Events\RequestComments\ECreate;
use App\Events\RequestComments\EDelete;
use App\Events\RequestComments\EUpdate;
use App\Http\Requests\RequestCommentRequest;

class RequestCommentController extends Controller
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
     * @param Request $request
     * @return array
     */
    public function permissions(Request $request): array
    {
        if (! Auth::check()) {
            $this->middleware('jwt.auth');

            return [];
        }

        $requestId = (int) $request->route('request');
        $this->_currentUser = Auth::user();

        if ($requestId) {
            $this->_requestModel = RequestModel::findOrFail($requestId);

            // If user created this request or assign
            if ($this->_requestModel->user_id === $this->_currentUser->id ||
                $this->_requestModel->assign_id === $this->_currentUser->id
            ) {
                return [];
            }
        }

        return [
            'index' => Permissions::REQUESTS_VIEW,
            'show' => Permissions::REQUESTS_VIEW,
            'store' => Permissions::REQUESTS_EDIT,
            'update' => Permissions::REQUESTS_EDIT,
            'destroy' => Permissions::REQUESTS_EDIT,
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @param  int  $requestId
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(int $requestId)
    {
        return response()->json([
            'message' => __('app.request_comments.index'),
            'request_comments' => $this->_requestModel->comments,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  RequestCommentRequest  $request
     * @param  int  $requestId
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(RequestCommentRequest $request, int $requestId)
    {
        $requestComment = new RequestComment;
        $requestComment->fill($request->all());
        $requestComment->request_id = $requestId;
        $requestComment->user_id = $this->_currentUser->id;

        if (! $requestComment->save()) {
            return response()->json(['message' => __('app.database.save_error')], 422);
        }

        $requestComment = $this->_requestModel->comments()->find($requestComment->id);
        event(new ECreate($requestId, $requestComment->toArray()));

        return response()->json([
            'message' => __('app.request_comments.store'),
            'request_comment' => $requestComment,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $requestId
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $requestId, int $id)
    {
        $requestComment = $this->_requestModel->comments()->findOrFail($id);

        return response()->json([
            'message' => __('app.request_comments.show'),
            'request_comment' => $requestComment,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  RequestCommentRequest  $request
     * @param  int  $requestId
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(RequestCommentRequest $request, int $requestId, int $id)
    {
        $requestComment = $this->_requestModel->comments()->findOrFail($id);
        $requestComment->fill($request->all());

        if (! $this->hasPermissionForAction($requestComment->user_id)) {
            return response()->json(['message' => __('app.middleware.no_permission')], 422);
        }

        if (! $requestComment->save()) {
            return response()->json(['message' => __('app.database.save_error')], 422);
        }

        $requestComment = $this->_requestModel->comments()->find($requestComment->id);
        event(new EUpdate($requestId, $id, $requestComment->toArray()));

        return response()->json([
            'message' => __('app.request_comments.update'),
            'request_comment' => $requestComment,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $requestId
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(int $requestId, int $id)
    {
        $requestComment = $this->_requestModel->comments()->findOrFail($id);

        if (! $this->hasPermissionForAction($requestComment->user_id)) {
            return response()->json(['message' => __('app.middleware.no_permission')], 422);
        }

        if (! $requestComment->delete()) {
            return response()->json(['message' => __('app.database.destroy_error')], 422);
        }

        event(new EDelete($requestId, $id));

        return response()->json([
            'message' => __('app.request_comments.destroy'),
        ]);
    }

    /**
     * Only author of comment or with REQUESTS_EDIT permission can update/delete.
     *
     * @param  int  $commentUserId
     * @return bool
     */
    private function hasPermissionForAction($commentUserId)
    {
        return $this->_currentUser->can(Permissions::REQUESTS_EDIT) || $commentUserId === $this->_currentUser->id;
    }
}
