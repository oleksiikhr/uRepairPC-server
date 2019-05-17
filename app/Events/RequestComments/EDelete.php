<?php

namespace App\Events\RequestComments;

use App\Events\Common\EDeleteBroadcast;

class EDelete extends EDeleteBroadcast
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
     * @return void
     */
    public function __construct(int $requestId, int $id)
    {
        parent::__construct($id);
        $this->_requestId = $requestId;
    }

    /**
     * @return string
     */
    public function event(): string
    {
        return 'request_comments';
    }

    /**
     * @return array|string|null
     */
    public function rooms()
    {
        return 'request_comments.'.$this->_requestId;
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
