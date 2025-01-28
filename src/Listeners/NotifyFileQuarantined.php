<?php

namespace zennit\Storage\Listeners;

use Illuminate\Support\Facades\Notification;
use zennit\Storage\Events\Security\FileQuarantinedEvent;
use zennit\Storage\Notifications\FileQuarantinedNotification;

class NotifyFileQuarantined
{
    /**
     * Handle the event.
     */
    public function handle(FileQuarantinedEvent $event): void
    {
        // Get notification recipients from config
        $recipients = config('scanning.notifications.quarantine_notify_emails', []);

        if (!empty($recipients)) {
            // Send notification to each recipient
            Notification::route('mail', $recipients)
                ->notify(
                    new FileQuarantinedNotification(
                        filePath:   $event->filePath,
                        scanResult: $event->scanResult
                    )
                );
        }
    }
}
