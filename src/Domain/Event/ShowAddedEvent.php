<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Event;

use CinemaBot\Application\CQRS\Event;
use CinemaBot\Application\ES\StorableEvent;
use CinemaBot\Domain\Aggregate\AggregateID;
use CinemaBot\Domain\CinemaID;
use CinemaBot\Domain\MovieName;
use CinemaBot\Domain\MovieTime;

final class ShowAddedEvent implements Event, StorableEvent
{
    public const TOPIC = 'cinema_bot.cinema.show_added';

    /** @var CinemaID */
    private $id;

    /** @var MovieName */
    private $name;

    /** @var MovieTime */
    private $time;

    public function __construct(CinemaID $id, MovieName $name, MovieTime $time)
    {
        $this->id = $id;
        $this->name = $name;
        $this->time = $time;
    }

    public function getID(): CinemaID
    {
        return $this->id;
    }

    public function getName(): MovieName
    {
        return $this->name;
    }

    public function getTime(): MovieTime
    {
        return $this->time;
    }

    public function getTopic(): string
    {
        return self::TOPIC;
    }

    public function getAggregateID(): AggregateID
    {
        return $this->id;
    }

    public static function fromJSON(string $json): StorableEvent
    {
        $payload = json_decode($json, true);

        return new self(
            CinemaID::from($payload['id']),
            MovieName::from($payload['name']),
            MovieTime::fromString($payload['time'])
        );
    }

    public function asJSON(): string
    {
        return json_encode([
            'id'   => $this->id->asString(),
            'name' => $this->name->asString(),
            'time' => $this->time->asString(),
        ]);
    }
}
