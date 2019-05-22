<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Event;

use CinemaBot\Application\CQRS\Event;
use CinemaBot\Domain\Aggregate\CalendarID;
use CinemaBot\Domain\MovieName;
use CinemaBot\Domain\MovieTime;

class ShowAddedEvent implements Event
{
    public const TOPIC = 'cinema_bot.show_added';

    /** @var CalendarID */
    private $id;

    /** @var MovieName */
    private $name;

    /** @var MovieTime */
    private $time;

    public function __construct(CalendarID $id, MovieName $name, MovieTime $time)
    {
        $this->id = $id;
        $this->name = $name;
        $this->time = $time;
    }

    public function getId(): CalendarID
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
}
