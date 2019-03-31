<?php

namespace App\Http\Helpers;

use App\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FilesHelper
{
    /**
     * @var UploadedFile[]
     */
    private $_files;

    /**
     * @var File[]
     */
    private $_filesUploaded;

    /**
     * @var array
     */
    private $_errors;

    public function __construct($files)
    {
        $this->_files = $files;
    }

    /**
     * @param {string} $storeFolder
     * @return void
     */
    public function upload($storeFolder)
    {
        $this->clear();

        foreach ($this->_files as $file) {
            $fileHelper = new FileHelper($file);
            $fileModel = $fileHelper->fill();
            $uploadedUri = $fileHelper->store($storeFolder);

            if (! $uploadedUri) {
                $this->_errors[$file->getClientOriginalName()] = [__('app.files.file_not_saved')];
                continue;
            }

            $fileModel->file = $uploadedUri;

            if (! $fileModel->save()) {
                $this->_errors[$file->getClientOriginalName()] = [__('app.database.save_error')];
                $fileHelper->delete();
                continue;
            }

            $this->_filesUploaded[] = $fileModel;
        }
    }

    /**
     * @return array
     */
    public function getUploadedIds()
    {
        return collect($this->_filesUploaded)->pluck('id');
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->_errors;
    }

    /**
     * @return File[]
     */
    public function getFilesUploaded()
    {
        return $this->_filesUploaded;
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        return count($this->_errors) > 0;
    }

    /**
     * @return void
     */
    private function clear()
    {
        $this->_filesUploaded = [];
        $this->_errors = [];
    }
}
