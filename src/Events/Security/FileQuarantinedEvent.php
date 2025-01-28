<?php

namespace zennit\Storage\Events\Security;

use Illuminate\Broadcasting\PrivateChannel;
use zennit\Storage\Events\EventSetup\BroadcastableEvent;
use zennit\Storage\Events\EventSetup\BroadcastConfiguration;

class FileQuarantinedEvent implements BroadcastableEvent
{
    use BroadcastConfiguration;

    public function __construct(string|array|int $message = 'File quarantined successfully!')
    {
        $this->message = $message;
        $this->channel = 'file-quarantined';
        $this->channelType = PrivateChannel::class;
    }
}
