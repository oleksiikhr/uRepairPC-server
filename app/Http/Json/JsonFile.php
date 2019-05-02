<?php

namespace App\Http\Json;

use Illuminate\Support\Facades\Storage;

abstract class JsonFile implements IJsonFile
{
    /**
     * @var bool
     */
    public $isFromFile = false;

    /**
     * @var string
     */
    private $_filePath;

    public function __construct($filePath)
    {
        $this->_filePath = $filePath;
    }

    /**
     * Get data from file or uses default.
     *
     * @return array
     */
    public function getData()
    {
        $json = $this->getFileData();

        if ($json) {
            $this->isFromFile = true;
            return $json;
        }

        return $this->getDefaultData();
    }

    /**
     * Save data to file.
     *
     * @param  array  $arr
     * @return void
     */
    public function saveData($arr)
    {
        $mergeData = array_merge($this->getDefaultData(), $arr);
        $json = json_encode($mergeData, JSON_PRETTY_PRINT);
        Storage::put($this->_filePath, $json);
    }

    /**
     * Get data from file.
     *
     * @return null|array
     */
    private function getFileData()
    {
        if (Storage::exists($this->_filePath)) {
            try {
                $data = Storage::get($this->_filePath);
                $json = json_decode($data);

                if ($json === null || json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception('Json data is incorrect');
                }

                return (array)$json;

            } catch (\Exception $e) {
                return null;
            }
        }

        return null;
    }
}
