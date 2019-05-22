<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Event;

use CinemaBot\Application\CQRS\Event;
use CinemaBot\Domain\Aggregate\CalendarID;

class CalendarCreatedEvent implements Event
{
    public const TOPIC = 'cinema_bot.calendar_created';

    /** @var CalendarID */
    private $id;

    public function __construct(CalendarID $id)
    {
        $this->id = $id;
    }

    public function getId(): CalendarID
    {
        return $this->id;
    }

    public function getTopic(): string
    {
        return self::TOPIC;
    }
}
