<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Event;

use CinemaBot\Application\CQRS\Event;
use CinemaBot\Application\ES\StorableEvent;
use CinemaBot\Domain\Aggregate\AggregateID;
use CinemaBot\Domain\CinemaID;
use CinemaBot\Domain\URL;

final class CinemaCreatedEvent implements Event, StorableEvent
{
    public const TOPIC = 'cinema_bot.cinema.cinema_created';

    /** @var CinemaID */
    private $id;

    /** @var URL */
    private $url;

    public function __construct(CinemaID $id, URL $url)
    {
        $this->id = $id;
        $this->url = $url;
    }

    public function getID(): CinemaID
    {
        return $this->id;
    }

    public function getURL(): URL
    {
        return $this->url;
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
            URL::from($payload['url']),
        );
    }

    public function asJSON(): string
    {
        return json_encode([
            'id'  => $this->id->asString(),
            'url' => $this->url->asString(),
        ]);
    }
}
