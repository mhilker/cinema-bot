<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Cinema;

use CinemaBot\Application\CQRS\Event;
use CinemaBot\Domain\Aggregate\AbstractAggregate;
use CinemaBot\Domain\Aggregate\AggregateID;
use CinemaBot\Domain\Event\CinemaCreatedEvent;
use CinemaBot\Domain\Event\ShowAddedEvent;
use CinemaBot\Domain\Movie;
use CinemaBot\Domain\MovieName;
use CinemaBot\Domain\MovieTime;
use CinemaBot\Domain\MovieTimes;
use CinemaBot\Domain\URL;

final class Cinema extends AbstractAggregate
{
    /** @var CinemaID */
    private $id;

    /** @var URL */
    private $url;

    /** @var MovieName[] */
    private $movies = [];

    /** @var array<string, array<string, MovieTime>> */
    private $calendar = [];

    public static function create(CinemaID $id, URL $url): Cinema
    {
        $cinema = new self(null);
        $cinema->record(new CinemaCreatedEvent($id, $url));
        return $cinema;
    }

    public function getShowByName(MovieName $name): Movie
    {
        return Movie::from($name, MovieTimes::from($this->calendar[$name->asString()]));
    }

    private function applyCinemaCreatedEvent(CinemaCreatedEvent $event): void
    {
        $this->id = $event->getId();
        $this->url = $event->getUrl();
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

    public function getURL(): URL
    {
        return $this->url;
    }
}
