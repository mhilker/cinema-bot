<?php

declare(strict_types=1);

namespace CinemaBot\Domain\AddShowToCinema;

use CinemaBot\Application\CQRS\CommandHandler;
use CinemaBot\Domain\AddShowToCinema\Parser\Crawler;

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
        $cinema = $this->repository->load($command->getID());

        $movies = $this->crawler->crawl($cinema->getURL());

        foreach ($movies as $movie) {
            foreach ($movie->getTimes() as $time) {
                $cinema->addShow($movie->getName(), $time);
            }
        }

        $this->repository->save($cinema);
    }
}
