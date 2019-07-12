<?php

namespace App\Events\EquipmentFiles;

use Illuminate\Support\Collection;
use App\Events\Common\ECreateBroadcast;

class ECreate extends ECreateBroadcast
{
    use EModel;

    /**
     * @var int
     */
    private $_equipmentId;

    /**
     * @var int
     */
    private $_userIdUpload;

    /**
     * Create a new event instance.
     *
     * @param  int  $equipmentId
     * @param  Collection  $data
     * @param  int  $userIdUpload
     * @return void
     */
    public function __construct(int $equipmentId, Collection $data, int $userIdUpload)
    {
        parent::__construct($data);
        $this->_equipmentId = $equipmentId;
        $this->_userIdUpload = $userIdUpload;
    }

    /**
     * @return array|string|null
     */
    public function rooms()
    {
        return [
            "{$this->roomName}.{$this->_equipmentId}",
            "{$this->roomName}.{$this->_equipmentId} [user_id.{$this->_userIdUpload}]",
        ];
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
