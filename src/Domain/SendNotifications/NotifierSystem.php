<?php

declare(strict_types=1);

namespace CinemaBot\Domain\SendNotifications;

use CinemaBot\Application\CQRS\Events;

final class NotifierSystem
{
    private NotificationProjection $projection;
    private Notifier $notifier;

    public function __construct(NotificationProjection $projection, Notifier $notifier)
    {
        $this->projection = $projection;
        $this->notifier = $notifier;
    }

    public function run(): void
    {
        $notifications = $this->projection->fetch();

        foreach ($notifications as $notification) {
            $id = $notification->getNotificationID();
            try {
                $this->notifier->notify($notification);
                $this->projection->ack($id);
            } catch (\Exception $exception) {
                $this->projection->nack($id);
            }
        }
    }
}
