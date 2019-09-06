<?php

declare(strict_types=1);

namespace CinemaBot\Domain\CreateCinema;

use CinemaBot\Application\CQRS\Event;
use CinemaBot\Application\Aggregate\AbstractAggregate;
use CinemaBot\Application\Aggregate\AggregateID;
use CinemaBot\Domain\CinemaID;
use CinemaBot\Domain\Event\CinemaCreatedEvent;
use CinemaBot\Domain\URL;

final class CreateCinemaUseCase extends AbstractAggregate
{
    /** @var CinemaID */
    private $id;

    /** @var URL */
    private $url;

    public static function createNew(CinemaID $id, URL $url): CreateCinemaUseCase
    {
        $cinema = new self(null);
        $cinema->record(new CinemaCreatedEvent($id, $url));
        return $cinema;
    }

    private function applyCinemaCreatedEvent(CinemaCreatedEvent $event): void
    {
        $this->id = $event->getID();
        $this->url = $event->getURL();
    }

    protected function apply(Event $event): void
    {
        if ($event instanceof CinemaCreatedEvent) {
            $this->applyCinemaCreatedEvent($event);
        }
    }

    public function getAggregateId(): AggregateID
    {
        return $this->id;
    }
}
