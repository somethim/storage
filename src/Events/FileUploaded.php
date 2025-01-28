<?php

namespace zennit\Storage\Events;

use Illuminate\Broadcasting\PrivateChannel;
use zennit\Storage\Events\EventSetup\BroadcastableEvent;
use zennit\Storage\Events\EventSetup\BroadcastConfiguration;

class FileUploaded implements BroadcastableEvent
{
    use BroadcastConfiguration;

    public function __construct(string|array|int $message = 'File uploaded successfully!')
    {
        $this->message = $message;
        $this->channel = 'file-uploaded';
        $this->channelType = PrivateChannel::class;
    }
}
