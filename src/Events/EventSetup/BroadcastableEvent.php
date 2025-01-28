<?php

namespace zennit\Storage\Events\EventSetup;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

interface BroadcastableEvent extends ShouldBroadcast
{
    public function getChannel(): string;

    public function getMessage(): string|array|int;

    public function broadcastAs(): string;

    public function getBroadcastEventName(): string;

    public function getChannelType(): string;

    public function broadcastWith(): array;

    public function broadcastOn(): Channel|PrivateChannel|PresenceChannel;
}
