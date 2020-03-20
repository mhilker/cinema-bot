<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Event;

use CinemaBot\Application\CQRS\Event;
use CinemaBot\Application\EventStore\StorableEvent;
use CinemaBot\Application\EventStream\EventStreamID;
use CinemaBot\Domain\GroupID;
use CinemaBot\Domain\Term;

final class TermAddedEvent implements Event, StorableEvent
{
    public const TOPIC = 'cinema_bot.watchlist.term_added';

    private GroupID $groupID;
    private Term $term;

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

    public function getEventStreamID(): EventStreamID
    {
        return $this->groupID;
    }

    public static function fromJSON(string $json): StorableEvent
    {
        $payload = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        return new self(
            GroupID::from($payload['groupID']),
            Term::from($payload['term']),
        );
    }

    public function asJSON(): string
    {
        return json_encode([
            'groupID'   => $this->groupID->asString(),
            'term' => $this->term->asString(),
        ], JSON_THROW_ON_ERROR);
    }
}
