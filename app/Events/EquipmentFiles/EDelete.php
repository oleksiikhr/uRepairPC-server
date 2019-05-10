<?php

namespace App\Events\EquipmentFiles;

use App\Events\Common\EDeleteBroadcast;

class EDelete extends EDeleteBroadcast
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
     * @return void
     */
    public function __construct(int $equipmentId, int $id)
    {
        parent::__construct($id);
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
        return 'equipment_files.' . $this->_equipmentId;
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
