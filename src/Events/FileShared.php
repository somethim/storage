<?php

namespace zennit\Storage\Events;

use Illuminate\Broadcasting\PrivateChannel;
use zennit\Storage\Events\EventSetup\BroadcastableEvent;
use zennit\Storage\Events\EventSetup\BroadcastConfiguration;

class FileShared implements BroadcastableEvent
{
    use BroadcastConfiguration;

    public function __construct(string|array|int $message = 'File shared successfully!')
    {
        $this->message = $message;
        $this->channel = 'file-shared';
        $this->channelType = PrivateChannel::class;
    }
}
