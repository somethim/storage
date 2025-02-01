<?php

namespace zennit\Storage\Notifications\Security;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use zennit\Storage\Services\Security\Scanners\DTO\ScanResult;

class FileQuarantinedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public readonly ScanResult $scanResult,
    ) {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $threats = collect($this->scanResult->result)
            ->map(fn ($threat) => "- {$threat['scanner']}: {$threat['threat']}")
            ->join("\n");

        return (new MailMessage())
            ->error()
            ->subject('Security Alert: File Quarantined')
            ->greeting('Security Alert')
            ->line('A file has been quarantined due to security threats.')
            ->line("File: {$this->scanResult->quarantine_path}")
            ->line('Threats detected:')
            ->line($threats)
            ->line('The file has been moved to quarantine for security.')
            ->line('Scan time: ' . $this->scanResult->scan_time->format('Y-m-d H:i:s'));
    }
}
