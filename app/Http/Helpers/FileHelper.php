<?php

namespace App\Http\Helpers;

use App\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileHelper
{
    /**
     * @var UploadedFile
     */
    private $_file;

    /**
     * @var string
     */
    private $_name;

    /**
     * @var string
     */
    private $_ext;

    /**
     * @var int
     */
    private $_size;

    public function __construct(UploadedFile $file)
    {
        $this->_file = $file;
        $this->_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $this->_ext = $file->extension();
        $this->_size = $file->getSize();
    }

    /**
     * @return File
     */
    public function fill(): File
    {
        $file = new File;
        $file->user_id = Auth::id();
        $file->name = $this->_name;
        $file->ext = $this->_ext;
        $file->size = $this->_size;

        return $file;
    }

    /**
     * Store the uploaded file on a filesystem disk.
     *
     * @param  string  $folder
     * @param  string  $disk
     * @return string|false
     */
    public function store(string $folder, string $disk = 'local')
    {
        $md5 = md5($this->_name);
        $f = substr($md5, 0, 3);
        $s = substr($md5, 3, 3);

        return $this->_file->storeAs(
            $folder . '/' . $f . '/' . $s,
            str_replace('.', '_', uniqid('', true)) . '.' . $this->_ext,
            $disk
        );
    }

    /**
     * Delete file from storage.
     *
     * @param  string  $file
     * @param  string  $disk
     * @return bool
     */
    public static function delete(?string $file, string $disk = 'local'): bool
    {
        if (! $file) {
            return true;
        }

        if (! Storage::disk($disk)->exists($file)) {
            return true;
        }

        $isDeleted = Storage::disk($disk)->delete($file);

//        TODO Notify on errors

        return $isDeleted;
    }
}
