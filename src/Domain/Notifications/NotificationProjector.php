<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Notifications;

use CinemaBot\Application\CQRS\Event;
use CinemaBot\Application\CQRS\EventListener;
use CinemaBot\Domain\Event\ShowAddedEvent;

final class NotificationProjector implements EventListener
{
    public function handle(Event $event): void
    {
        if ($event instanceof ShowAddedEvent) {

        }
    }
}
