<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Event;

use CinemaBot\Application\CQRS\Event;
use CinemaBot\Domain\GroupID;
use CinemaBot\Domain\Term;

final class TermAddedEvent implements Event
{
    public const TOPIC = 'cinema_bot.watchlist.term_added';

    /** @var GroupID */
    private $groupID;

    /** @var Term */
    private $term;

    public function __construct(GroupID $groupID, Term $term)
    {
        $this->groupID = $groupID;
        $this->term = $term;
    }

    public function getGroupID(): GroupID
    {
        return $this->groupID;
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
