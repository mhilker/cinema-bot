<?php

declare(strict_types=1);

namespace CinemaBot\Domain\SendNotifications;

interface NotificationProjection
{
    public function fetch(): ?Notification;

    public function ack(NotificationID $id): void;
}
