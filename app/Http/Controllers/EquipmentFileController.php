<?php

namespace App\Http\Controllers;

use App\User;
use App\Equipment;
use App\Http\Helpers\FileHelper;
use App\Http\Helpers\FilesHelper;
use App\Http\Requests\FileRequest;
use Illuminate\Support\Facades\Storage;

class EquipmentFileController extends Controller
{
    public function __construct()
    {
        $this->allowRoles([
            User::ROLE_WORKER => [
                'index', 'store', 'show', 'update',
            ],
            User::ROLE_USER => [],
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  int  $equipmentId
     * @return \Illuminate\Http\Response
     */
    public function index(int $equipmentId)
    {
        $equipment = Equipment::findOrFail($equipmentId);

        return response()->json([
            'message' => 'Файли отримані',
            'files' => $equipment->files,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  FileRequest  $request
     * @param  int  $equipmentId
     * @return \Illuminate\Http\Response
     */
    public function store(FileRequest $request, int $equipmentId)
    {
        $equipment = Equipment::findOrFail($equipmentId);
        $requestFiles = $request->file('files');

        $filesHelper = new FilesHelper($requestFiles);
        $filesHelper->upload('equipments/' . $equipmentId);

        $equipment->files()->attach($filesHelper->getUploadedIds());

        if ($filesHelper->hasErrors()) {
            return response()->json([
                'message' => __('app.files.upload_error'),
                'errors' => $filesHelper->getErrors(),
                'files' => $filesHelper->getFilesUploaded(),
            ], 422);
        }

        return response()->json([
            'message' => __('app.files.upload_success'),
            'files' => $filesHelper->getFilesUploaded(),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $equipmentId
     * @param  int  $fileId
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
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

        return response()->json([
            'message' => __('app.files.file_destroyed'),
        ]);
    }
}
