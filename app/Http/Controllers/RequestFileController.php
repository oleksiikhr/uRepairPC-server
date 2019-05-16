<?php

namespace App\Http\Controllers;

use App\User;
use App\Enums\Permissions;
use Illuminate\Http\Request;
use App\Request as RequestModel;
use App\Http\Helpers\FileHelper;
use App\Http\Helpers\FilesHelper;
use App\Http\Requests\FileRequest;
use App\Events\RequestFiles\ECreate;
use App\Events\RequestFiles\EUpdate;
use App\Events\RequestFiles\EDelete;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RequestFileController extends Controller
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

        $requestId = (int)$request->route('request');
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
            'message' => __('app.files.files_get'),
            'request_files' => $this->_requestModel->files,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  FileRequest  $request
     * @param  int  $requestId
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(FileRequest $request, int $requestId)
    {
        $requestFiles = $request->file('files');

        $filesHelper = new FilesHelper($requestFiles);
        $filesHelper->upload('requests/' . $requestId);

        $uploadedIds = $filesHelper->getUploadedIds();
        $this->_requestModel->files()->attach($uploadedIds);
        $uploadedFiles = $this->_requestModel->files()->whereIn('files.id', $uploadedIds)->get();

        if (count($uploadedFiles)) {
            event(new ECreate($requestId, $uploadedFiles->toArray()));
        }

        if ($filesHelper->hasErrors()) {
            return response()->json([
                'message' => __('app.files.upload_error'),
                'errors' => $filesHelper->getErrors(),
                'request_files' => $uploadedFiles,
            ], 422);
        }

        return response()->json([
            'message' => __('app.files.upload_success'),
            'request_files' => $uploadedFiles,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $requestId
     * @param  int  $fileId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $requestId, int $fileId)
    {
        $requestFile = $this->_requestModel->files()->findOrFail($fileId);

        if (! Storage::exists($requestFile->file)) {
            return response()->json(['message' => __('app.files.file_not_found')], 422);
        }

        return Storage::download($requestFile->file, $requestFile->name . '.' . $requestFile->ext);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  FileRequest  $request
     * @param  int  $requestId
     * @param  int  $fileId
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(FileRequest $request, int $requestId, int $fileId)
    {
        $requestFile = $this->_requestModel->files()->findOrFail($fileId);
        $requestFile->name = $request->name;

        if (! $this->hasPermissionForAction($requestFile->user_id)) {
            return response()->json(['message' => __('app.middleware.no_permission')], 422);
        }

        if (! $requestFile->save()) {
            return response()->json(['message' => __('app.database.save_error')], 422);
        }

        event(new EUpdate($requestId, $fileId, $requestFile));

        return response()->json([
            'message' => __('app.files.file_updated'),
            'request_file' => $requestFile,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $requestId
     * @param  int  $fileId
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(int $requestId, int $fileId)
    {
        $requestFile = $this->_requestModel->files()->findOrFail($fileId);

        if (! $this->hasPermissionForAction($requestFile->user_id)) {
            return response()->json(['message' => __('app.middleware.no_permission')], 422);
        }

        FileHelper::delete($requestFile->file);

        if (! $requestFile->delete()) {
            return response()->json(['message' => __('app.database.destroy_error')], 422);
        }

        event(new EDelete($requestId, $fileId));

        return response()->json([
            'message' => __('app.files.file_destroyed'),
        ]);
    }

    /**
     * Only author of file or with REQUESTS_EDIT permission can update/delete
     *
     * @param  int  $fileUserId
     * @return bool
     */
    private function hasPermissionForAction($fileUserId)
    {
        return $this->_currentUser->can(Permissions::REQUESTS_EDIT) || $fileUserId !== $this->_currentUser->id;
    }
}
