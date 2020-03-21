<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Notifications;

interface NotificationProjection
{
    public function fetch(): Notifications;

    public function ack(NotificationID $id): void;

    public function nack(NotificationID $id): void;
}
