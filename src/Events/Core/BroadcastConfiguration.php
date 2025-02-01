<?php

namespace zennit\Storage\Events\Core;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Trait BroadcastConfiguration
 *
 * This trait defines the methods that should be implemented by the classes that use it
 * - broadcastOn
 * - getChannelType
 * - broadcastWith
 * - broadcastAs
 *
 * This trait should be used by the classes that implement the BroadcastableEvent interface
 *
 * @package zennit\Storage\Events\Core
 *
 * @see BroadcastableEvent
 */
trait BroadcastConfiguration
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        protected mixed $message,
        protected string $channel,
        protected string $broadcastAs,
        protected string $channelType
    ) {
    }

    public function broadcastOn(): Channel|PrivateChannel|PresenceChannel
    {
        $channelType = $this->getChannelType();

        return new $channelType($this->channel);
    }

    public function getChannelType(): string
    {
        return $this->channelType;
    }

    public function broadcastWith(): array
    {
        return ['message' => $this->message];
    }

    public function broadcastAs(): string
    {
        return $this->broadcastAs;
    }
}
