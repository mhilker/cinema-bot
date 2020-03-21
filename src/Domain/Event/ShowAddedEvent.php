<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Event;

use CinemaBot\Application\CQRS\Event;
use CinemaBot\Application\EventStore\StorableEvent;
use CinemaBot\Application\EventStream\EventStreamID;
use CinemaBot\Domain\CinemaID;
use CinemaBot\Domain\MovieName;
use CinemaBot\Domain\ShowTime;

final class ShowAddedEvent implements Event, StorableEvent
{
    public const TOPIC = 'cinema_bot.cinema.show_added';

    private CinemaID $id;
    private MovieName $name;
    private ShowTime $time;

    public function __construct(CinemaID $id, MovieName $Name, ShowTime $time)
    {
        $this->id = $id;
        $this->name = $Name;
        $this->time = $time;
    }

    public function getId(): CinemaID
    {
        return $this->id;
    }

    public function getName(): MovieName
    {
        return $this->name;
    }

    public function getTime(): ShowTime
    {
        return $this->time;
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
            CinemaID::from($payload['cinemaID']),
            MovieName::from($payload['name']),
            ShowTime::fromString($payload['time'])
        );
    }

    public function asJSON(): string
    {
        return json_encode([
            'cinemaID'   => $this->id->asString(),
            'name' => $this->name->asString(),
            'time' => $this->time->asString(),
        ], JSON_THROW_ON_ERROR);
    }
}
