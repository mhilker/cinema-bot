<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Event;

use CinemaBot\Application\Aggregate\AggregateID;
use CinemaBot\Application\CQRS\Event;
use CinemaBot\Application\ES\StorableEvent;
use CinemaBot\Domain\CinemaID;
use CinemaBot\Domain\MovieName;
use CinemaBot\Domain\MovieTime;

final class ShowAddedEvent implements Event, StorableEvent
{
    public const TOPIC = 'cinema_bot.cinema.show_added';

    /** @var CinemaID */
    private $cinemaID;

    /** @var MovieName */
    private $movieName;

    /** @var MovieTime */
    private $movieTime;

    public function __construct(CinemaID $cinemaID, MovieName $movieName, MovieTime $movieTime)
    {
        $this->cinemaID = $cinemaID;
        $this->movieName = $movieName;
        $this->movieTime = $movieTime;
    }

    public function getCinemaID(): CinemaID
    {
        return $this->cinemaID;
    }

    public function getMovieName(): MovieName
    {
        return $this->movieName;
    }

    public function getMovieTime(): MovieTime
    {
        return $this->movieTime;
    }

    public function getTopic(): string
    {
        return self::TOPIC;
    }

    public function getAggregateID(): AggregateID
    {
        return $this->cinemaID;
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
            'id'   => $this->cinemaID->asString(),
            'name' => $this->movieName->asString(),
            'time' => $this->movieTime->asString(),
        ]);
    }
}
