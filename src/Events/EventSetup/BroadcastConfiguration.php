<?php

namespace zennit\Storage\Events\EventSetup;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

trait BroadcastConfiguration
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    protected string|array|int $message;

    protected string $channel;

    protected string $broadcastAs;

    protected string $channelType;

    public function broadcastOn(): Channel|PrivateChannel|PresenceChannel
    {
        $channelType = $this->getChannelType();

        return new $channelType($this->getChannel());
    }

    public function getChannelType(): string
    {
        return $this->channelType;
    }

    public function getChannel(): string
    {
        return $this->channel;
    }

    public function broadcastWith(): array
    {
        return ['message' => $this->getMessage()];
    }

    public function getMessage(): string|array|int
    {
        return $this->message;
    }

    public function broadcastAs(): string
    {
        return $this->getBroadcastEventName();
    }

    public function getBroadcastEventName(): string
    {
        return $this->broadcastAs ?? $this->getChannel();
    }
}
