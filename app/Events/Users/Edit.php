<?php

namespace App\Events\Users;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class Edit implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var User
     */
    private $_user;

    /**
     * @var string
     */
    private $_detail;

    /**
     * Create a new event instance.
     *
     * @param  User  $user
     * @param  string  $detail
     * @return void
     */
    public function __construct(User $user, string $detail)
    {
        $this->_user = $user;
        $this->_detail = $detail;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return ['server.users.edit'];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        $fromId = Auth::check() ? Auth::id() : null;

        return [
            'from_id' => $fromId,
            'id' => $this->_user->id,
            'detail' => $this->_detail,
        ];
    }
}
