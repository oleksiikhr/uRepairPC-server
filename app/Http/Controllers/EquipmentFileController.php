<?php

namespace App\Http\Controllers;

use App\Equipment;
use App\Enums\Permissions;
use Illuminate\Http\Request;
use App\Http\Helpers\FileHelper;
use App\Http\Helpers\FilesHelper;
use App\Http\Requests\FileRequest;
use App\Events\EquipmentFiles\ECreate;
use App\Events\EquipmentFiles\EDelete;
use App\Events\EquipmentFiles\EUpdate;
use Illuminate\Support\Facades\Storage;

class EquipmentFileController extends Controller
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
            'index' => Permissions::EQUIPMENTS_FILES_VIEW,
            'show' => Permissions::EQUIPMENTS_FILES_DOWNLOAD,
            'store' => Permissions::EQUIPMENTS_FILES_CREATE,
            'update' => Permissions::EQUIPMENTS_FILES_EDIT,
            'destroy' => Permissions::EQUIPMENTS_FILES_DELETE,
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @param  int  $equipmentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(int $equipmentId)
    {
        $equipment = Equipment::findOrFail($equipmentId);

        return response()->json([
            'message' => __('app.files.files_get'),
            'files' => $equipment->files,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  FileRequest  $request
     * @param  int  $equipmentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(FileRequest $request, int $equipmentId)
    {
        $equipment = Equipment::findOrFail($equipmentId);
        $requestFiles = $request->file('files');

        $filesHelper = new FilesHelper($requestFiles);
        $filesHelper->upload('equipments/' . $equipmentId);

        $uploadedIds = $filesHelper->getUploadedIds();
        $equipment->files()->attach($uploadedIds);
        $uploadedFiles = $equipment->files()->whereIn('files.id', $uploadedIds)->get();

        if (count($uploadedFiles)) {
            event(new ECreate($equipmentId, $uploadedFiles->toArray()));
        }

        if ($filesHelper->hasErrors()) {
            return response()->json([
                'message' => __('app.files.upload_error'),
                'errors' => $filesHelper->getErrors(),
                'files' => $uploadedFiles,
            ], 422);
        }

        return response()->json([
            'message' => __('app.files.upload_success'),
            'files' => $uploadedFiles,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $equipmentId
     * @param  int  $fileId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $equipmentId, int $fileId)
    {
        $file = Equipment::findOrFail($equipmentId)
            ->files()
            ->findOrFail($fileId);

        if (! Storage::exists($file->file)) {
            return response()->json(['message' => __('app.files.file_not_found')], 422);
        }

        return Storage::download($file->file, $file->name . '.' . $file->ext);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  FileRequest  $request
     * @param  int  $equipmentId
     * @param  int  $fileId
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(FileRequest $request, int $equipmentId, int $fileId)
    {
        $file = Equipment::findOrFail($equipmentId)
            ->files()
            ->findOrFail($fileId);

        $file->name = $request->name;

        if (! $file->save()) {
            return response()->json(['message' => __('app.database.save_error')], 422);
        }

        event(new EUpdate($equipmentId, $fileId, $file));

        return response()->json([
            'message' => __('app.files.file_updated'),
            'file' => $file,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $equipmentId
     * @param  int  $fileId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $equipmentId, int $fileId)
    {
        $file = Equipment::findOrFail($equipmentId)
            ->files()
            ->findOrFail($fileId);

        FileHelper::delete($file->file);

        if (! $file->delete()) {
            return response()->json(['message' => __('app.database.destroy_error')], 422);
        }

        event(new EDelete($equipmentId, $fileId));

        return response()->json([
            'message' => __('app.files.file_destroyed'),
        ]);
    }
}
