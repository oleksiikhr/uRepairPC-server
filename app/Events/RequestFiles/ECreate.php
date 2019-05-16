<?php

namespace App\Events\RequestFiles;

use App\Events\Common\ECreateBroadcast;

class ECreate extends ECreateBroadcast
{
    /**
     * @var int
     */
    private $_requestId;

    /**
     * Create a new event instance.
     *
     * @param  int  $requestId
     * @param  mixed  $data
     * @return void
     */
    public function __construct(int $requestId, $data)
    {
        parent::__construct($data);
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
        return 'request_files.'.$this->_requestId;
    }

    /**
     * @return array|null
     */
    public function params(): ?array
    {
        return [
            'request_id' => $this->_requestId,
        ];
    }
}
