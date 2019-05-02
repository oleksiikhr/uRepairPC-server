<?php

namespace App\Http\Json;

interface IJsonFile
{
    /**
     * Get data from file or uses default.
     *
     * @return object
     */
    public function getData();

    /**
     * Get default data.
     *
     * @return array
     */
    public function getDefaultData();

    /**
     * Save data to file.
     *
     * @param  array  $arr
     * @return object
     */
    public function saveData($arr);
}
