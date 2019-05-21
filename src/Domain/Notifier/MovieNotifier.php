<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Notifier;

use CinemaBot\Application\CQRS\Event;
use CinemaBot\Application\CQRS\EventListener;
use CinemaBot\Application\CQRS\Events;
use CinemaBot\Domain\Event\MoviesFoundEvent;

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
            case MoviesFoundEvent::TOPIC:
                /** @var MoviesFoundEvent $event */
                $this->dispatchMoviesFoundEvent($event);
                break;
        }
    }

    private function dispatchMoviesFoundEvent(MoviesFoundEvent $event): void
    {
        $chatId = getenv('TELEGRAM_CHAT_ID');

        foreach ($event->getMovies() as $movie) {
            $this->notifier->send($movie, $chatId);
        }
    }
}
