<?php

declare(strict_types=1);

namespace CinemaBot\Domain\SendNotifications;

interface Notifier
{
    public function notify(Notification $notification): void;
}
