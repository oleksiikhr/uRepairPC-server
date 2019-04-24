<?php

namespace App\Http\Controllers;

use App\RequestType;
use App\Enums\Permissions;
use Illuminate\Http\Request;
use App\Http\Requests\RequestTypeRequest;

class RequestTypeController extends Controller
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
        $list = RequestType::all();

        return response()->json($list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  RequestTypeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RequestTypeRequest $request)
    {
        $requestType = new RequestType;
        $requestType->fill($request->all());

        if ($request->has('default') && $request->default) {
            RequestType::clearDefaultValues();
        }

        if (! $requestType->save()) {
            return response()->json(['message' => __('app.database.save_error')], 422);
        }

        return response()->json([
            'message' => __('app.request_type.store'),
            'request_type' => $requestType,
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
        $requestType = RequestType::findOrFail($id);

        return response()->json([
            'message' => __('app.request_type.show'),
            'request_type' => $requestType,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  RequestTypeRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RequestTypeRequest $request, int $id)
    {
        $requestType = RequestType::findOrFail($id);

        if ($request->has('default') && $request->default !== $requestType->default) {
            if (! $request->default) {
                return response()->json(['message' => __('app.request_type.update_default')], 422);
            }

            RequestType::clearDefaultValues();
        }

        $requestType->fill($request->all());

        if (! $requestType->save()) {
            return response()->json(['message' => __('app.database.save_error')], 422);
        }

        return response()->json([
            'message' => __('app.request_type.update'),
            'request_type' => $requestType,
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
        $requestType = RequestType::findOrFail($id);

        if ($requestType->default) {
            return response()->json(['message' => __('app.request_type.destroy_default')], 422);
        }

        if (! RequestType::destroy($id)) {
            return response()->json(['message' => __('app.database.destroy_error')], 422);
        }

        return response()->json([
            'message' => __('app.request_type.destroy'),
        ]);
    }
}
