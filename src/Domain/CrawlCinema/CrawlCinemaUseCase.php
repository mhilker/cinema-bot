<?php

declare(strict_types=1);

namespace CinemaBot\Domain\CrawlCinema;

use CinemaBot\Application\CQRS\Event;
use CinemaBot\Application\CQRS\Events;
use CinemaBot\Application\EventStream\AbstractEventStream;
use CinemaBot\Domain\Cinema\CinemaUseCase;
use CinemaBot\Domain\CinemaID;
use CinemaBot\Domain\CrawlCinema\Crawler\Crawler;
use CinemaBot\Domain\Event\CinemaCreatedEvent;
use CinemaBot\Domain\Event\ShowAddedEvent;
use CinemaBot\Domain\ShowTime;
use CinemaBot\Domain\URL;

final class CrawlCinemaUseCase extends AbstractEventStream implements CinemaUseCase
{
    private CinemaID $id;
    private URL $url;
    private Crawler $crawler;
    /** @var array<string, array<string, ShowTime>> */
    private array $calendar = [];

    public function __construct(Events $events, Crawler $crawler)
    {
        parent::__construct($events);
        $this->crawler = $crawler;
    }

    private function applyCinemaCreatedEvent(CinemaCreatedEvent $event): void
    {
        $this->id = $event->getID();
        $this->url = $event->getURL();
    }

    public function crawl(): void
    {
        $shows = $this->crawler->crawl($this->url);

        foreach ($shows as $show) {
            foreach ($show->getTimes() as $time) {
                if (isset($this->calendar[$show->getName()->asString()][$time->asString()]) === false) {
                    $this->record(new ShowAddedEvent($this->id, $show->getName(), $time));
                }
            }
        }
    }

    private function applyShowAddedEvent(ShowAddedEvent $event): void
    {
        $name = $event->getName();
        $time = $event->getTime();

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
