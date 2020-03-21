<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Notifications;

interface Notifier
{
    public function notify(Notification $notification): void;
}
