<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Notifications;

use Psr\Log\LoggerInterface;

final class NotifierSystem
{
    private NotificationProjection $projection;
    private Notifier $notifier;
    private LoggerInterface $logger;

    public function __construct(NotificationProjection $projection, Notifier $notifier, LoggerInterface $logger)
    {
        $this->projection = $projection;
        $this->notifier = $notifier;
        $this->logger = $logger;
    }

    public function run(): void
    {
        $notifications = $this->projection->fetch();
        $this->logger->info('{count} notification(s) found', ['count' => $notifications->count()]);

        foreach ($notifications as $notification) {
            $id = $notification->getNotificationID();
            try {
                $this->logger->info('Sending notification', ['id' => $id->asString()]);
                $this->notifier->notify($notification);
                $this->projection->ack($id);
                $this->logger->info('Acked notification', ['id' => $id->asString()]);
            } catch (\Exception $exception) {
                $this->logger->error('Could not send notification', [
                    'id' => $id->asString(),
                    'exception' => $exception,
                ]);
                $this->projection->nack($id);
            }
        }
    }
}
