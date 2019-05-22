<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Notifier;

use CinemaBot\Application\CQRS\Event;
use CinemaBot\Application\CQRS\EventListener;
use CinemaBot\Application\CQRS\Events;
use CinemaBot\Domain\Event\ShowAddedEvent;

class MovieNotifier implements EventListener
{
    /** @var TelegramNotifier */
    private $notifier;

    public function __construct(TelegramNotifier $notifier)
    {
        $this->notifier = $notifier;
    }

    public function dispatch(Events $events): void
    {
        foreach ($events as $event) {
            $this->dispatchEvents($event);
        }
    }

    private function dispatchEvents(Event $event): void
    {
        switch ($event->getTopic()) {
            case ShowAddedEvent::TOPIC:
                /** @var ShowAddedEvent $event */
                $this->dispatchShowAddedEvent($event);
                break;
        }
    }

    private function dispatchShowAddedEvent(ShowAddedEvent $event): void
    {
//        $watchlist = $this->projection->getAll();
//        if (count($watchlist) === 0) {
//            return;
//        }
//        $watchlist = $this->projection->getAll();
//
//        $movies = $movies->filter(static function (Movie $movie) use ($watchlist) {
//            foreach ($watchlist as $term) {
//                if (mb_stripos($movie->getName(), $term->asString()) !== false) {
//                    return true;
//                }
//            }
//            return false;
//        });

//        $now = new DateTimeImmutable();
//
//        $movies = $movies->filter(static function (Movie $movie) use ($now) {
//            foreach ($movie->getTimes() as $time) {
//                if ($time->getValue() <= $now) {
//                    $movie->getTimes()->remove($time);
//                }
//            }
//            return count($movie->getTimes()) > 0;
//        });

//        $chatId = getenv('TELEGRAM_CHAT_ID');
//
//        foreach ($event->getMovies() as $movie) {
//            $this->notifier->send($movie, $chatId);
//        }
    }
}
