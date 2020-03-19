<?php

declare(strict_types=1);

namespace CinemaBot\Domain\AddShowToCinema;

use CinemaBot\Application\Aggregate\AbstractEventStream;
use CinemaBot\Application\CQRS\Event;
use CinemaBot\Domain\CinemaID;
use CinemaBot\Domain\Event\CinemaCreatedEvent;
use CinemaBot\Domain\Event\ShowAddedEvent;
use CinemaBot\Domain\MovieName;
use CinemaBot\Domain\Movies;
use CinemaBot\Domain\MovieTime;
use CinemaBot\Domain\Cinema\CinemaUseCase;
use CinemaBot\Domain\URL;

final class AddShowToCinemaUseCase extends AbstractEventStream implements CinemaUseCase
{
    private CinemaID $id;
    private URL $url;

    /** @var array<string, array<string, MovieTime>> */
    private array $calendar = [];

    private function applyCinemaCreatedEvent(CinemaCreatedEvent $event): void
    {
        $this->id = $event->getCinemaID();
        $this->url = $event->getCinemaURL();
    }

    public function addShows(Movies $movies): void
    {
        foreach ($movies as $movie) {
            foreach ($movie->getTimes() as $time) {
                if (isset($this->calendar[$movie->getName()->asString()][$time->asString()]) === false) {
                    $this->record(new ShowAddedEvent($this->id, $movie->getName(), $time));
                }
            }
        }
    }

    public function addShow(MovieName $name, MovieTime $time): void
    {

    }

    private function applyShowAddedEvent(ShowAddedEvent $event): void
    {
        $name = $event->getMovieName();
        $time = $event->getMovieTime();

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

    public function getUrl(): URL
    {
        return $this->url;
    }
}
