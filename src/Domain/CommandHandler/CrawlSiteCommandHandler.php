<?php

declare(strict_types=1);

namespace CinemaBot\Domain\CommandHandler;

use CinemaBot\Application\CQRS\CommandHandler;
use CinemaBot\Application\CQRS\EventBus;
use CinemaBot\Domain\Command\CrawlSiteCommand;
use CinemaBot\Domain\Crawler\Crawler;
use CinemaBot\Domain\Repository\CalendarRepository;

class CrawlSiteCommandHandler implements CommandHandler
{
    /** @var EventBus */
    private $eventBus;

    /** @var CalendarRepository */
    private $repository;

    /** @var Crawler */
    private $crawler;

    public function __construct(EventBus $eventBus, CalendarRepository $repository, Crawler $crawler)
    {
        $this->eventBus = $eventBus;
        $this->repository = $repository;
        $this->crawler = $crawler;
    }

    public function handle(CrawlSiteCommand $command): void
    {
        $calendar = $this->repository->load($command);

        $movies = $this->crawler->crawl();

        foreach ($movies as $movie) {
            foreach ($movie->getTimes() as $time) {
                $calendar->addShow($movie->getName(), $time);
            }
        }

        $this->eventBus->dispatch($calendar->extractEvents());
    }
}
