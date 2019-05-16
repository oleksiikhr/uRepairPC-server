<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    /* | -----------------------------------------------------------------------------------
     * | Relationships
     * | -----------------------------------------------------------------------------------
     */

    public function equipments()
    {
        return $this->belongsToMany(Equipment::class);
    }

    public function requests()
    {
        return $this->belongsToMany(Request::class);
    }
}
