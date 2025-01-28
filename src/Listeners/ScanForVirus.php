<?php

namespace zennit\Storage\Listeners;

use zennit\Storage\Events\FileUploaded;
use zennit\Storage\Listeners\ListenerSetup\BroadcastsToRedis;

class ScanForVirus
{
    use BroadcastsToRedis;

    /**
     * Handle the event.
     *
     * @param FileUploaded $event
     *
     * @return void
     */
    public function handle(FileUploaded $event): void
    {
        $this->publishToRedis($event);
    }
}
