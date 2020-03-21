<?php

declare(strict_types=1);

namespace CinemaBot\Domain\CrawlCinema;

use CinemaBot\Application\CQRS\CommandHandler;
use CinemaBot\Application\CQRS\Events;
use CinemaBot\Domain\Cinema\CinemaRepository;
use CinemaBot\Domain\CrawlCinema\Crawler\Crawler;

final class CrawlCinemaCommandHandler implements CommandHandler
{
    private CinemaRepository $repository;
    private Crawler $crawler;

    public function __construct(CinemaRepository $repository, Crawler $crawler)
    {
        $this->repository = $repository;
        $this->crawler = $crawler;
    }

    public function handle(CrawlCinemaCommand $command): void
    {
        $id = $command->getID();

        /** @var CrawlCinemaUseCase $cinema */
        $cinema = $this->repository->load($id, fn(Events $events) => new CrawlCinemaUseCase($events, $this->crawler));
        $cinema->crawl();

        $this->repository->save($cinema);
    }
}
