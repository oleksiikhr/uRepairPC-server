<?php

namespace App\Http\Controllers;

use App\File;
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

        $fileHelper = new FileHelper($request->file('file'));
        $file = $fileHelper->fill();
        $uploadedUri = $fileHelper->store('equipments');

        if (! $uploadedUri) {
            return response()->json(['message' => __('app.files.file_not_saved')], 422);
        }

        $file->file = $uploadedUri;

        if (! $file->save()) {
            Storage::delete($uploadedUri);
            return response()->json(['message' => __('app.database.save_error')], 422);
        }

        $equipment->files()->attach([$file->id]);

        return response()->json([
            'message' => __('app.files.file_saved'),
            'file' => $file,
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
