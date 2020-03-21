<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Event;

use CinemaBot\Application\CQRS\Event;
use CinemaBot\Application\EventStore\StorableEvent;
use CinemaBot\Application\EventStream\EventStreamID;
use CinemaBot\Domain\GroupID;
use CinemaBot\Domain\Term;

final class TermRemovedEvent implements Event, StorableEvent
{
    public const TOPIC = 'cinema_bot.watchlist.term_removed';

    private GroupID $id;
    private Term $term;

    public function __construct(GroupID $id, Term $term)
    {
        $this->id = $id;
        $this->term = $term;
    }

    public function getId(): GroupID
    {
        return $this->id;
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
        return $this->id;
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
            'groupID'   => $this->id->asString(),
            'term' => $this->term->asString(),
        ], JSON_THROW_ON_ERROR);
    }
}
