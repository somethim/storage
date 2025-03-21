<?php

namespace zennit\Storage\Listeners\Core;

use Exception;
use Illuminate\Support\Facades\Redis;
use zennit\Storage\Events\Core\BroadcastableEvent;

trait BroadcastsToRedis
{
    protected function publishToRedis(BroadcastableEvent $event): void
    {
        $channel = $event->broadcastOn()->name;
        $eventData = [
            'event' => $event->broadcastAs(),
            'data' => $event->broadcastWith(),
        ];
        $payload = json_encode($eventData);

        try {
            Redis::publish($channel, $payload);
        } catch (Exception $e) {
            report($e);
        }
    }
}
