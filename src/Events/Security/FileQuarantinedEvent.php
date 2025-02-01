<?php

namespace zennit\Storage\Events\Security;

use Illuminate\Broadcasting\PrivateChannel;
use zennit\Storage\Events\Core\BroadcastableEvent;
use zennit\Storage\Events\Core\BroadcastConfiguration;
use zennit\Storage\Services\Security\Scanners\DTO\ScanResult;

readonly class FileQuarantinedEvent implements BroadcastableEvent
{
    use BroadcastConfiguration;

    public function __construct(protected ScanResult $scanResult)
    {
        $this->message = $this->scanResult->toArray();
        $this->channel = 'file-quarantined';
        $this->channelType = PrivateChannel::class;
        $this->broadcastAs = 'file.quarantined';
    }

    public function getScanResult(): ScanResult
    {
        return $this->scanResult;
    }
}
