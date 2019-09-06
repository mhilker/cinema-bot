<?php

declare(strict_types=1);

namespace CinemaBot\Domain\AddShowToCinema;

use CinemaBot\Application\CQRS\Event;
use CinemaBot\Application\Aggregate\AbstractAggregate;
use CinemaBot\Application\Aggregate\AggregateID;
use CinemaBot\Domain\CinemaID;
use CinemaBot\Domain\Event\CinemaCreatedEvent;
use CinemaBot\Domain\Event\ShowAddedEvent;
use CinemaBot\Domain\MovieName;
use CinemaBot\Domain\MovieTime;

final class AddShowToCinemaUseCase extends AbstractAggregate
{
    /** @var CinemaID */
    private $id;

    /** @var MovieName[] */
    private $movies = [];

    /** @var array<string, array<string, MovieTime>> */
    private $calendar = [];

    private function applyCinemaCreatedEvent(CinemaCreatedEvent $event): void
    {
        $this->id = $event->getID();
    }

    public function addShow(MovieName $name, MovieTime $time): void
    {
        if (isset($this->calendar[$name->asString()][$time->asString()]) === false) {
            $this->record(new ShowAddedEvent($this->id, $name, $time));
        }
    }

    private function applyShowAddedEvent(ShowAddedEvent $event): void
    {
        $name = $event->getName();
        $time = $event->getTime();

        $this->movies[$name->asString()] = $name;
        $this->calendar[$name->asString()][$time->asString()] = $time;
    }

    protected function apply(Event $event): void
    {
        if ($event instanceof CinemaCreatedEvent) {
            $this->applyCinemaCreatedEvent($event);
        }
        if ($event instanceof ShowAddedEvent) {
            $this->applyShowAddedEvent($event);
        }
    }

    public function getAggregateId(): AggregateID
    {
        return $this->id;
    }
}
