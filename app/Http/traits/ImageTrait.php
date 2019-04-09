<?php

namespace App\Http\Traits;

use App\Http\Helpers\FileHelper;
use App\Http\Requests\ImageRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

trait ImageTrait
{
    private $_folderAvatars = 'avatars';

    /**
     * Get image by model.
     *
     * @return false|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|int
     */
    public function getImage()
    {
        $user = Auth::user();

        if (! Storage::exists($user->image)) {
            return response(null);
        }

        $file = Storage::path($user->image);

        return response()->download($file);
    }

    /**
     * Set image by model.
     *
     * @param  string  $id
     * @param  ImageRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setImage(ImageRequest $request, $id)
    {
        $file = $request->file('image');
        $model = $this->_model::findOrFail($id);
        $tableName = (new $this->_model)->getTable();

        FileHelper::delete($model->image);

        $md5 = md5($id);
        $f = substr($md5, 0, 3);
        $s = substr($md5, 3, 3);

        $uploadedUri = $file->storeAs(
            $tableName . '/' . $this->_folderAvatars . '/' . $f . '/' . $s,
            str_replace('.', '_', uniqid('', true)) . '.' . $file->extension()
        );

        if (! $uploadedUri) {
            return response()->json(['message' => __('app.files.file_not_saved')], 422);
        }

        $model->image = $uploadedUri;

        if (! $model->save()) {
            FileHelper::delete($uploadedUri);
            return response()->json(['message' => __('app.database.save_error')], 422);
        }

        return response()->json([
            'message' => __('app.files.file_saved'),
            'image' => $model->image,
        ]);
    }

    /**
     * Delete avatar by model.
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteImage($id)
    {
        $model = $this->_model::findOrFail($id);

        if (! FileHelper::delete($model->image)) {
            return response()->json(['message' => __('app.files.file_not_deleted')], 422);
        }

        $model->image = null;

        if (! $model->save()) {
            return response()->json(['message' => __('app.database.save_error')], 422);
        }

        return response()->json([
            'message' => __('app.files.file_destroyed'),
        ]);
    }
}
