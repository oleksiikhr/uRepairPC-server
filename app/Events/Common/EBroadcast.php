<?php

namespace App\Events\Common;

use App\Interfaces\IBroadcastWebsocket;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

abstract class EBroadcast implements ShouldBroadcast, IBroadcastWebsocket
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    const TYPE_JOIN = 'join';
    const TYPE_SYNC = 'sync';
    const TYPE_CREATE = 'create';
    const TYPE_UPDATE = 'update';
    const TYPE_DELETE = 'delete';

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array
     */
    public function broadcastOn(): array
    {
        return ['server.'.$this->event()];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        return [
            'event' => $this->event(),
            'type' => $this->type(),
            'socketId' => request()->header('X-Socket-ID'),
            'rooms' => $this->rooms(),
            'params' => $this->params(),
            'data' => $this->transformData($this->data()),
            'join' => $this->join(), // Only for CREATE event
        ];
    }

    /**
     * @param  mixed  $data
     * @return mixed
     */
    protected function transformData($data)
    {
        if ($data && method_exists($data, 'toArray')) {
            return $data->toArray();
        }

        return $data;
    }

    /**
     * Join to this room after broadcast.
     * @return array|string
     */
    protected function join()
    {
        return '';
    }
}
