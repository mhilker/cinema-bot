<?php

declare(strict_types=1);

namespace CinemaBot\Domain\SendNotifications;

use CinemaBot\Application\CQRS\Event;
use CinemaBot\Application\CQRS\Events;
use CinemaBot\Domain\Event\ShowAddedEvent;
use CinemaBot\Domain\Watchlist\WatchlistProjection;
use DateTimeImmutable;

final class NotifierSystem
{
    private WatchlistProjection $projection;
    private Notifier $notifier;
    private array $data = [];

    public function __construct(WatchlistProjection $projection, Notifier $notifier)
    {
        $this->projection = $projection;
        $this->notifier = $notifier;
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
        $watchlist = $this->projection->loadByGroupID();
        if (count($watchlist) === 0) {
            return;
        }

        $now = new DateTimeImmutable();
        if ($event->getMovieTime() < $now) {
            return;
        }

        foreach ($watchlist as $term) {
            if ($event->getMovieName()->containsInsensitiveTerm($term)) {
                $this->data[$event->getCinemaID()->asString()][$event->getMovieName()->asString()] = $event->getMovieName();
            }
        }
    }

    private function send(): void
    {
        print_r($this->data);

//        $chatID = ChatID::from(getenv('TELEGRAM_CHAT_ID'));
//
//        foreach ($this->data as $cinemaID => $movieNames) {
//            $cinemaID = CinemaID::from($cinemaID);
//            $cinema = $this->repository->load($cinemaID);
//
//            foreach ($movieNames as $movieName) {
//                $movie = $cinema->getShowByName($movieName);
//                $this->notifier->send($movie, $chatID);
//            }
//        }

        $this->data = [];
    }
}
