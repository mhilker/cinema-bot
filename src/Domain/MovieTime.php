<?php

declare(strict_types=1);

namespace CinemaBot\Domain;

use DateTimeImmutable;

class MovieTime
{
    /** @var DateTimeImmutable */
    private $value;

    public function __construct(DateTimeImmutable $value)
    {
        $this->value = $value;
    }

    public static function from(DateTimeImmutable $value): MovieTime
    {
        return new self($value);
    }

    public function getValue(): DateTimeImmutable
    {
        return $this->value;
    }

    public function asString(): string
    {
        return $this->value->format(DATE_ATOM);
    }
}
