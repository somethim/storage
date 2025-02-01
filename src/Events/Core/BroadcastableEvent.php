<?php

namespace zennit\Storage\Events\Core;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
 * Interface BroadcastableEvent
 *
 * This interface defines the methods that should be implemented by the classes that implement it
 * - broadcastOn
 * - getChannelType
 * - broadcastWith
 * - broadcastAs
 *
 * For simplicity, the methods are defined in the trait BroadcastConfiguration
 *
 * @see BroadcastConfiguration
 * @see ShouldBroadcast
 *
 * @package zennit\Storage\Events\Core
 */
interface BroadcastableEvent extends ShouldBroadcast
{
    /**
     * This method should return the channel to broadcast on
     *
     * @return Channel|PrivateChannel|PresenceChannel
     */
    public function broadcastOn(): Channel|PrivateChannel|PresenceChannel;

    /**
     * This method should return the type of channel
     * - Channel::class
     * - PrivateChannel::class
     * - PresenceChannel::class
     *
     * @return string
     */
    public function getChannelType(): string;

    /**
     * This method should return the message to be broadcasted
     *
     * @return array
     */
    public function broadcastWith(): array;

    /**
     * This method should return the message to be broadcasted
     *
     * @return string
     */
    public function broadcastAs(): string;
}
