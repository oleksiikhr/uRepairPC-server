<?php

namespace App\Http\Controllers;

use App\Equipment;
use App\Http\FileHelper;
use Illuminate\Http\Request;
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
     * TODO Increase time to upload files + docs.
     *
     * @param  FileRequest  $request
     * @param  int  $equipmentId
     * @return \Illuminate\Http\Response
     */
    public function store(FileRequest $request, int $equipmentId)
    {
        $equipment = Equipment::findOrFail($equipmentId);
        $requestFiles = $request->file('files');
        $filesUploaded = [];
        $errors = [];

        foreach ($requestFiles as $requestFile) {
            $fileHelper = new FileHelper($requestFile);
            $file = $fileHelper->fill();
            $uploadedUri = $fileHelper->store('equipments/' . $equipmentId);

            if (! $uploadedUri) {
                $errors[$requestFile->getClientOriginalName()] = [__('app.files.file_not_saved')];
                continue;
            }

            $file->file = $uploadedUri;

            if (! $file->save()) {
                $errors[$requestFile->getClientOriginalName()] = [__('app.database.save_error')];
                Storage::delete($uploadedUri);
                continue;
            }

            $filesUploaded[] = $file;
        }

        $ids = collect($filesUploaded)->pluck('id');
        $equipment->files()->attach($ids);

        if (count($errors)) {
            return response()->json([
                'message' => __('app.files.upload_error'),
                'errors' => $errors,
                'files' => $filesUploaded,
            ], 422);
        }

        return response()->json([
            'message' => __('app.files.upload_success'),
            'files' => $filesUploaded,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
