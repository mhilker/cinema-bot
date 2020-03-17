<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Event;

use CinemaBot\Application\Aggregate\AggregateID;
use CinemaBot\Application\CQRS\Event;
use CinemaBot\Application\EventStore\StorableEvent;
use CinemaBot\Domain\CinemaID;
use CinemaBot\Domain\URL;

final class CinemaCreatedEvent implements Event, StorableEvent
{
    public const TOPIC = 'cinema_bot.cinema.cinema_created';

    private CinemaID $cinemaID;
    private URL $url;

    public function __construct(CinemaID $cinemaID, URL $url)
    {
        $this->cinemaID = $cinemaID;
        $this->url = $url;
    }

    public function getCinemaID(): CinemaID
    {
        return $this->cinemaID;
    }

    public function getCinemaURL(): URL
    {
        return $this->url;
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
        $payload = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        return new self(
            CinemaID::from($payload['id']),
            URL::from($payload['url']),
        );
    }

    public function asJSON(): string
    {
        return json_encode([
            'id'  => $this->cinemaID->asString(),
            'url' => $this->url->asString(),
        ], JSON_THROW_ON_ERROR);
    }
}
