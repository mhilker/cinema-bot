<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Notifications;

use CinemaBot\Domain\Notifications\JobQueue\NotificationJobQueue;
use CinemaBot\Domain\Notifications\Notifier\Notifier;
use Psr\Log\LoggerInterface;

final class NotifierSystem
{
    private NotificationJobQueue $queue;
    private Notifier $notifier;
    private LoggerInterface $logger;

    public function __construct(NotificationJobQueue $queue, Notifier $notifier, LoggerInterface $logger)
    {
        $this->queue = $queue;
        $this->notifier = $notifier;
        $this->logger = $logger;
    }

    public function run(): void
    {
        $notifications = $this->queue->fetch();
        $this->logger->info('{count} notification(s) found', ['count' => $notifications->count()]);

        foreach ($notifications as $notification) {
            $id = $notification->getNotificationID();
            try {
                $this->logger->info('Sending notification', ['id' => $id->asString()]);
                $this->notifier->notify($notification);
                $this->queue->ack($id);
                $this->logger->info('Acked notification', ['id' => $id->asString()]);
            } catch (\Exception $exception) {
                $this->logger->error('Could not send notification', [
                    'id' => $id->asString(),
                    'exception' => $exception,
                ]);
                $this->queue->nack($id);
            }
        }
    }
}
