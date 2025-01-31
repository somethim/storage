<?php

namespace zennit\Storage\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FileScanComplete extends Notification
{
    use Queueable;

    public function __construct(private readonly array $scanResults)
    {
    }

    public function via(): array
    {
        return ['mail', 'database'];
    }

    public function toMail(): MailMessage
    {
        $message = $this->scanResults['is_clean']
            ? 'File scan completed successfully.'
            : 'Security threat detected in file!';

        $mail = (new MailMessage())
            ->subject('File Scan Complete')
            ->line($message);

        if (!$this->scanResults['is_clean']) {
            $mail->line('Threats found:');
            foreach ($this->scanResults['threats_found'] as $threat) {
                $mail->line("- {$threat['scanner']}: {$threat['threat']}");
            }
        }

        return $mail;
    }

    public function toArray(): array
    {
        return $this->scanResults;
    }
}
