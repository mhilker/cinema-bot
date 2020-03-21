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

    public function send(Events $events): void
    {
        while ($notification = $this->projection->fetch()) {
            $this->notifier->notify($notification);
            $this->projection->ack($notification->getNotificationID());
        }
    }
}
