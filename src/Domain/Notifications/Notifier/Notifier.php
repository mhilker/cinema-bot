<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Notifications\Notifier;

use CinemaBot\Domain\Notifications\Notification;

interface Notifier
{
    public function notify(Notification $notification): void;
}
