<?php

declare(strict_types=1);

namespace CinemaBot\Domain\CreateCinema;

use CinemaBot\Application\Aggregate\AbstractAggregate;
use CinemaBot\Application\CQRS\Event;
use CinemaBot\Domain\CinemaID;
use CinemaBot\Domain\Event\CinemaCreatedEvent;
use CinemaBot\Domain\URL;

final class CreateCinemaUseCase extends AbstractAggregate
{
    private CinemaID $id;
    private URL $url;

    public static function createNew(CinemaID $id, URL $url): CreateCinemaUseCase
    {
        $cinema = new self(null);
        $cinema->record(new CinemaCreatedEvent($id, $url));
        return $cinema;
    }

    private function applyCinemaCreatedEvent(CinemaCreatedEvent $event): void
    {
        $this->id = $event->getCinemaID();
        $this->url = $event->getCinemaURL();
    }

    protected function apply(Event $event): void
    {
        if ($event instanceof CinemaCreatedEvent) {
            $this->applyCinemaCreatedEvent($event);
        }
    }
}
