<?php

namespace App\Events\EquipmentFiles;

use App\Events\Common\ECreateBroadcast;

class ECreate extends ECreateBroadcast
{
    /**
     * @var int
     */
    private $_equipmentId;

    /**
     * Create a new event instance.
     *
     * @param  int  $equipmentId
     * @param  mixed  $data
     * @return void
     */
    public function __construct(int $equipmentId, $data)
    {
        parent::__construct($data);
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
            'equipment_id' => $this->_equipmentId,
        ];
    }
}
