<?php

namespace App\Http\Controllers;

use App\RequestStatus;
use App\Enums\Permissions;
use Illuminate\Http\Request;
use App\Http\Requests\RequestStatusRequest;

class RequestStatusController extends Controller
{
    /**
     * Add middleware depends on user permissions.
     *
     * @param  Request  $request
     * @return array
     */
    public function permissions(Request $request): array
    {
        return [
            'index' => Permissions::REQUESTS_CONFIG_VIEW,
            'show' => Permissions::REQUESTS_CONFIG_VIEW,
            'store' => Permissions::REQUESTS_CONFIG_CREATE,
            'update' => Permissions::REQUESTS_CONFIG_EDIT,
            'destroy' => Permissions::REQUESTS_CONFIG_DELETE,
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = RequestStatus::all();

        return response()->json($list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  RequestStatusRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RequestStatusRequest $request)
    {
        $requestStatus = new RequestStatus;
        $requestStatus->fill($request->all());

        if ($request->has('default') && $request->default) {
            RequestStatus::clearDefaultValues();
        }

        if (! $requestStatus->save()) {
            return response()->json(['message' => __('app.database.save_error')], 422);
        }

        return response()->json([
            'message' => __('app.request_status.store'),
            'request_status' => $requestStatus,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $requestStatus = RequestStatus::findOrFail($id);

        return response()->json([
            'message' => __('app.request_status.show'),
            'request_status' => $requestStatus,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  RequestStatusRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RequestStatusRequest $request, int $id)
    {
        $requestStatus = RequestStatus::findOrFail($id);

        if ($request->has('default') && $request->default !== $requestStatus->default) {
            if (! $request->default) {
                return response()->json(['message' => __('app.request_status.update_default')], 422);
            }

            RequestStatus::clearDefaultValues();
        }

        $requestStatus->fill($request->all());

        if (! $requestStatus->save()) {
            return response()->json(['message' => __('app.database.save_error')], 422);
        }

        return response()->json([
            'message' => __('app.request_status.update'),
            'request_status' => $requestStatus,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $requestStatus = RequestStatus::findOrFail($id);

        if ($requestStatus->default) {
            return response()->json(['message' => __('app.request_status.destroy_default')], 422);
        }

        if (! RequestStatus::destroy($id)) {
            return response()->json(['message' => __('app.database.destroy_error')], 422);
        }

        return response()->json([
            'message' => __('app.request_status.destroy'),
        ]);
    }
}
