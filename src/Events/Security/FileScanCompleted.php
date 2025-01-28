<?php

namespace zennit\Storage\Events\Security;

use Illuminate\Broadcasting\PrivateChannel;
use zennit\Storage\Events\EventSetup\BroadcastableEvent;
use zennit\Storage\Events\EventSetup\BroadcastConfiguration;

class FileScanCompleted implements BroadcastableEvent
{
    use BroadcastConfiguration;

    public function __construct(string|array|int $message = 'File scan completed successfully!')
    {
        $this->message = $message;
        $this->channel = 'file-scan-completed';
        $this->channelType = PrivateChannel::class;
    }
}
