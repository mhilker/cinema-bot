<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Notifications\JobQueue;

use CinemaBot\Domain\Notifications\NotificationID;
use CinemaBot\Domain\Notifications\Notifications;

interface NotificationJobQueue
{
    public function fetch(): Notifications;

    public function ack(NotificationID $id): void;

    public function nack(NotificationID $id): void;
}
