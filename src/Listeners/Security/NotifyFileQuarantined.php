<?php

namespace zennit\Storage\Listeners\Security;

use Illuminate\Support\Facades\Notification;
use zennit\Storage\Events\Security\FileQuarantinedEvent;
use zennit\Storage\Notifications\Security\FileQuarantinedNotification;
use zennit\Storage\Services\Security\Scanners\DTO\ScanResult;

class NotifyFileQuarantined
{
    /**
     * Handle the event.
     */
    public function handle(FileQuarantinedEvent $event): void
    {
        if (!config('scanning.notifications.enabled')) {
            return;
        }

        $scanResult = $event->getScanResult();

        // Send email notifications to configured recipients
        $emailRecipients = config('scanning.notifications.quarantine_notify_emails', []);
        if (!empty($emailRecipients)) {
            Notification::route('mail', $emailRecipients)
                ->notify(new FileQuarantinedNotification($scanResult));
        }

        // Send in-app notification to configured user
        $notifiableUser = config('scanning.notifications.notify_user');
        if ($notifiableUser) {
            Notification::send($notifiableUser, new FileQuarantinedNotification($scanResult));
        }
    }
}
