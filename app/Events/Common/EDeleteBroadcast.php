<?php

namespace App\Events\Common;

abstract class EDeleteBroadcast extends EBroadcast
{
    /**
     * @var int
     */
    protected $id;

    /**
     * Create a new event instance.
     *
     * @param  int  $id
     * @return void
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return self::TYPE_DELETE;
    }

    /**
     * @return array|null
     */
    public function params(): ?array
    {
        return [
            'id' => $this->id,
        ];
    }

    /**
     * @return mixed
     */
    public function data()
    {
    }
}
