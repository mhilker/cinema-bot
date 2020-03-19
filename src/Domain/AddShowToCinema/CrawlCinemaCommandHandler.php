<?php

declare(strict_types=1);

namespace CinemaBot\Domain\AddShowToCinema;

use CinemaBot\Application\CQRS\CommandHandler;
use CinemaBot\Application\CQRS\Events;
use CinemaBot\Domain\AddShowToCinema\Parser\Crawler;
use CinemaBot\Domain\Cinema\CinemaRepository;

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
        $cinemaID = $command->getCinemaID();

        /** @var AddShowToCinemaUseCase $cinema */
        $cinema = $this->repository->load($cinemaID, fn(Events $events) => new AddShowToCinemaUseCase($events));

        $url = $cinema->getURL();
        $movies = $this->crawler->crawl($url);

        $cinema->addShows($movies);

        $this->repository->save($cinema);
    }
}
