<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Notifications\Projection;

use CinemaBot\Domain\Notifications\NotificationID;
use CinemaBot\Domain\Notifications\Notifications;

interface NotificationQueueProjection
{
    public function enqueue(Notifications $notifications): void;

    public function dequeue(): Notifications;

    public function isEmpty(): bool;

    public function ack(NotificationID $id): void;

    public function nack(NotificationID $id): void;
}
