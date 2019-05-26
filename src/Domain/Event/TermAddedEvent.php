<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Event;

use CinemaBot\Application\CQRS\Event;
use CinemaBot\Domain\Watchlist\Term;

final class TermAddedEvent implements Event
{
    public const TOPIC = 'cinema_bot.watchlist.term_added';

    /** @var Term */
    private $term;

    public function __construct(Term $term)
    {
        $this->term = $term;
    }

    public function getTerm(): Term
    {
        return $this->term;
    }

    public function getTopic(): string
    {
        return self::TOPIC;
    }
}
