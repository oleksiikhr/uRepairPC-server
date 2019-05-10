<?php

namespace App\Events;

use App\Events\Common\EBroadcast;

class JoinRooms extends EBroadcast
{
    /**
     * @var array
     */
    private $_rooms;

    /**
     * @var bool
     */
    private $_sync;

    /**
     * Create a new event instance.
     *
     * @param  array  $rooms
     * @param  bool  $sync
     * @return void
     */
    public function __construct(array $rooms, bool $sync)
    {
        $this->_rooms = $rooms;
        $this->_sync = $sync;
    }

    /**
     * @return string
     */
    public function event(): string
    {
        return 'listeners';
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return $this->_sync ? 'sync' : 'join';
    }

    /**
     * @return array|string|null
     */
    public function rooms()
    {
        return $this->_rooms;
    }

    /**
     * @return array|null
     */
    public function params(): ?array
    {
        return null;
    }

    /**
     * @return mixed
     */
    public function data()
    {
        return null;
    }
}
