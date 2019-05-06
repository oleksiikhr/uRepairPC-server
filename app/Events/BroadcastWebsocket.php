<?php

namespace App\Events;

use Illuminate\Support\Facades\Auth;
use Illuminate\Queue\SerializesModels;
use App\Interfaces\IBroadcastWebsocket;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

abstract class BroadcastWebsocket implements ShouldBroadcast, IBroadcastWebsocket
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    const ACTION_UPDATE = 'update';
    const ACTION_DELETE = 'delete';

    /**
     * Object id (user, equipments, etc)
     *
     * @var int
     */
    protected $id;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var array|string
     */
    protected $permissions;

    /**
     * @var string
     */
    protected $action;

    /**
     * Create a new event instance.
     *
     * @param int $id
     * @param mixed $data
     * @param array|string $permissions
     * @param string $action
     */
    public function __construct(int $id, $data = null, $permissions = '', $action = self::ACTION_UPDATE)
    {
        $this->id = $id;
        $this->data = $data;
        $this->permissions = $permissions;
        $this->action = $action;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return ['server.' . $this->section()];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        $fromUser = Auth::user();
        $section = $this->section();

        return [
            'emit' => $section . '-' . $this->id,
            'permissions' => $this->permissions,
            'data' => [
                'title' => __('roles_and_permissions.sections.' . $section),
                'message' => 'Оновлено: [id: ' . $this->id . ']',
                'permissions' => $this->permissions,
                'data' => $this->data,
                'section' => $section,
                'action' => $this->action,
                'from' => [
                    'id' => $fromUser->id,
                    'name' => $fromUser->last_name . ' ' . $fromUser->first_name,
                ],
            ],
        ];
    }
}
