<?php

namespace App\Events\EquipmentFiles;

use App\Events\Common\EUpdateBroadcast;

class EUpdate extends EUpdateBroadcast
{
    /**
     * @var int
     */
    private $_equipmentId;

    /**
     * Create a new event instance.
     *
     * @param  int  $equipmentId
     * @param  int  $id
     * @param  mixed  $data
     * @return void
     */
    public function __construct(int $equipmentId, int $id, $data)
    {
        parent::__construct($id, $data);
        $this->_equipmentId = $equipmentId;
    }

    /**
     * @return string
     */
    public function event(): string
    {
        return 'equipment_files';
    }

    /**
     * @return array|string|null
     */
    public function rooms()
    {
        return 'equipment_files.'.$this->_equipmentId;
    }

    /**
     * @return array|null
     */
    public function params(): ?array
    {
        return [
            'id' => $this->id,
            'equipment_id' => $this->_equipmentId,
        ];
    }
}
