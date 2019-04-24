<?php

namespace App\Http\Controllers;

use App\RequestPriority;
use App\Enums\Permissions;
use Illuminate\Http\Request;
use App\Http\Requests\RequestPriorityRequest;

class RequestPriorityController extends Controller
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
        $list = RequestPriority::all();

        return response()->json($list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  RequestPriorityRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RequestPriorityRequest $request)
    {
        $requestPriority = new RequestPriority;
        $requestPriority->fill($request->all());

        if ($request->has('default') && $request->default) {
            RequestPriority::clearDefaultValues();
        }

        if (! $requestPriority->save()) {
            return response()->json(['message' => __('app.database.save_error')], 422);
        }

        return response()->json([
            'message' => __('app.request_priority.store'),
            'request_priority' => $requestPriority,
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
        $requestPriority = RequestPriority::findOrFail($id);

        return response()->json([
            'message' => __('app.request_priority.show'),
            'request_priority' => $requestPriority,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  RequestPriorityRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RequestPriorityRequest $request, int $id)
    {
        $requestPriority = RequestPriority::findOrFail($id);

        if ($request->has('default') && $request->default !== $requestPriority->default) {
            if (! $request->default) {
                return response()->json(['message' => __('app.request_priority.update_default')], 422);
            }

            RequestPriority::clearDefaultValues();
        }

        $requestPriority->fill($request->all());

        if (! $requestPriority->save()) {
            return response()->json(['message' => __('app.database.save_error')], 422);
        }

        return response()->json([
            'message' => __('app.request_priority.update'),
            'request_priority' => $requestPriority,
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
        $requestPriority = RequestPriority::findOrFail($id);

        if ($requestPriority->default) {
            return response()->json(['message' => __('app.request_priority.destroy_default')], 422);
        }

        if (! RequestPriority::destroy($id)) {
            return response()->json(['message' => __('app.database.destroy_error')], 422);
        }

        return response()->json([
            'message' => __('app.request_priority.destroy'),
        ]);
    }
}
