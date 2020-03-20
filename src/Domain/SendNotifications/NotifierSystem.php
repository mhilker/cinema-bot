<?php

declare(strict_types=1);

namespace CinemaBot\Domain\SendNotifications;

use CinemaBot\Application\CQRS\Events;
use CinemaBot\Domain\WatchList\WatchListProjection;

final class NotifierSystem
{
    private WatchListProjection $projection;
    private Notifier $notifier;
    private array $data = [];

    public function __construct(WatchListProjection $projection, Notifier $notifier)
    {
        $this->projection = $projection;
        $this->notifier = $notifier;
    }

    public function send(Events $events): void
    {
        print_r($events);

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
