<?php

namespace App\Events\RequestFiles;

use App\Events\Common\EUpdateBroadcast;

class EUpdate extends EUpdateBroadcast
{
    /**
     * @var int
     */
    private $_requestId;

    /**
     * Create a new event instance.
     *
     * @param  int  $requestId
     * @param  int  $id
     * @param  mixed  $data
     * @return void
     */
    public function __construct(int $requestId, int $id, $data)
    {
        parent::__construct($id, $data);
        $this->_requestId = $requestId;
    }

    /**
     * @return string
     */
    public function event(): string
    {
        return 'request_files';
    }

    /**
     * @return array|string|null
     */
    public function rooms()
    {
        return 'request_files.' . $this->_requestId;
    }

    /**
     * @return array|null
     */
    public function params(): ?array
    {
        return [
            'id' => $this->id,
            'request_id' => $this->_requestId,
        ];
    }
}
