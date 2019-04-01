<?php

namespace App\Http\Controllers;

use App\Equipment;
use Illuminate\Http\Request;
use App\Http\Helpers\FilesHelper;
use App\Http\Requests\FileRequest;
use Illuminate\Support\Facades\Storage;

class EquipmentFileController extends Controller
{
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
    public function show($equipmentId, $fileId)
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
