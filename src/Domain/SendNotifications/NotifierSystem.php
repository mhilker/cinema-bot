<?php

declare(strict_types=1);

namespace CinemaBot\Domain\SendNotifications;

use CinemaBot\Application\CQRS\Event;
use CinemaBot\Application\CQRS\EventListener;
use CinemaBot\Application\CQRS\Events;
use CinemaBot\Domain\AddShowToCinema\CinemaRepository;
use CinemaBot\Domain\AddShowToCinema\Watchlist\WatchlistProjection;
use CinemaBot\Domain\ChatID;
use CinemaBot\Domain\CinemaID;
use CinemaBot\Domain\Event\ShowAddedEvent;
use DateTimeImmutable;

final class NotifierSystem implements EventListener
{
    /** @var WatchlistProjection */
    private $projection;

    /** @var Notifier */
    private $notifier;

    /** @var CinemaRepository */
    private $repository;

    private $data = [];

    public function __construct(WatchlistProjection $projection, Notifier $notifier, CinemaRepository $repository)
    {
        $this->projection = $projection;
        $this->notifier = $notifier;
        $this->repository = $repository;
    }

    public function handle(Events $events): void
    {
        foreach ($events as $event) {
            $this->handleEvents($event);
        }

        $this->send();
    }

    private function handleEvents(Event $event): void
    {
        if ($event instanceof ShowAddedEvent) {
            $this->handleShowAddedEvent($event);
        }
    }

    private function handleShowAddedEvent(ShowAddedEvent $event): void
    {
        $watchlist = $this->projection->getAll();
        if (count($watchlist) === 0) {
            return;
        }

        $now = new DateTimeImmutable();
        if ($event->getTime() < $now) {
            return;
        }

        $match = false;
        foreach ($watchlist as $term) {
            if ($event->getName()->containsInsensitiveTerm($term->asString())) {
                $match = true;
                break;
            }
        }

        if ($match === false) {
            return;
        }

        $this->data[$event->getID()->asString()][$event->getName()->asString()] = $event->getName();
    }

    private function send(): void
    {
        $chatID = ChatID::from(getenv('TELEGRAM_CHAT_ID'));

        foreach ($this->data as $cinemaID => $movieNames) {
            $cinemaID = CinemaID::from($cinemaID);
            $cinema = $this->repository->load($cinemaID);

            foreach ($movieNames as $movieName) {
                $movie = $cinema->getShowByName($movieName);
                $this->notifier->send($movie, $chatID);
            }
        }

        $this->data = [];
    }
}
