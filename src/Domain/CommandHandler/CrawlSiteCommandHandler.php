<?php

declare(strict_types=1);

namespace CinemaBot\Domain\CommandHandler;

use CinemaBot\Application\CQRS\CommandHandler;
use CinemaBot\Application\CQRS\EventBus;
use CinemaBot\Application\CQRS\Events;
use CinemaBot\Domain\Command\CrawlSiteCommand;
use CinemaBot\Domain\Event\MoviesFoundEvent;
use CinemaBot\Domain\Movie;
use CinemaBot\Domain\Watchlist\WatchlistProjection;
use CinemaBot\Infrastructure\CopyDownloader;
use CinemaBot\Infrastructure\DOMParser;
use DateTimeImmutable;

class CrawlSiteCommandHandler implements CommandHandler
{
    /** @var EventBus */
    private $eventBus;

    /** @var WatchlistProjection */
    private $projection;

    public function __construct(EventBus $eventBus, WatchlistProjection $projection)
    {
        $this->eventBus = $eventBus;
        $this->projection = $projection;
    }

    public function handle(CrawlSiteCommand $command): void
    {
        $watchlist = $this->projection->getAll();
        if (count($watchlist) === 0) {
            return;
        }

        $downloader = new CopyDownloader();
        $fileName = $downloader->download('https://www.cinemotion-kino.de/hameln/kinoprogramm');

        $parser = new DOMParser();
        $movies = $parser->parse($fileName);

        $watchlist = $this->projection->getAll();

        $movies = $movies->filter(static function (Movie $movie) use ($watchlist) {
            foreach ($watchlist as $term) {
                if (mb_stripos($movie->getName(), $term->asString()) !== false) {
                    return true;
                }
            }
            return false;
        });

        $now = new DateTimeImmutable();

        $movies = $movies->filter(static function (Movie $movie) use ($now) {
            foreach ($movie->getTimes() as $time) {
                if ($time->getDateTime() <= $now) {
                    $movie->getTimes()->remove($time);
                }
            }
            return count($movie->getTimes()) > 0;
        });

        if (count($movies) === 0) {
            return;
        }

        $events = Events::from([
            new MoviesFoundEvent($movies),
        ]);

        $this->eventBus->dispatch($events);
    }
}
