<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Aggregate;

final class CalendarID implements AggregateID
{
    /** @var string */
    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function from(string $value): CalendarID
    {
        return new self($value);
    }

    public function asString(): string
    {
        return $this->value;
    }
}
