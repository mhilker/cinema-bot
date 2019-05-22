<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Aggregate;

use CinemaBot\Application\CQRS\Event;
use CinemaBot\Domain\Event\CalendarCreatedEvent;
use CinemaBot\Domain\Event\ShowAddedEvent;
use CinemaBot\Domain\MovieName;
use CinemaBot\Domain\MovieTime;

final class Calendar extends AbstractAggregate
{
    /** @var CalendarID */
    private $id;

    /** @var MovieName[] */
    private $movies = [];

    /** @var array<string<string, MovieTime>> */
    private $calendar = [];

    public static function create(CalendarID $id): Calendar
    {
        $calendar = new self(null);
        $calendar->record(new CalendarCreatedEvent($id));
        return $calendar;
    }

    private function applyCalendarCreatedEvent(CalendarCreatedEvent $event): void
    {
        $this->id = $event->getId();
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
        switch ($event->getTopic()) {
            case CalendarCreatedEvent::TOPIC:
                /** @var CalendarCreatedEvent $event */
                $this->applyCalendarCreatedEvent($event);
                break;
            case ShowAddedEvent::TOPIC:
                /** @var ShowAddedEvent $event */
                $this->applyShowAddedEvent($event);
                break;
        }
    }

    public function getAggregateId(): AggregateID
    {
        return $this->id;
    }
}
