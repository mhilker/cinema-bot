<?php

declare(strict_types=1);

namespace CinemaBot\Domain;

use DateTimeImmutable;

final class ShowTime
{
    private DateTimeImmutable $value;

    private function __construct(DateTimeImmutable $value)
    {
        $this->value = $value;
    }

    public static function from(DateTimeImmutable $value): self
    {
        return new self($value);
    }

    public static function fromString(string $value): self
    {
        try {
            // TODO: Timezone
            $dateTime = new DateTimeImmutable($value);
        } catch (\Exception $exception) {
            throw new InvalidShowTimeException('Show time is invalid');
        }

        return new self($dateTime);
    }

    public function getValue(): DateTimeImmutable
    {
        return $this->value;
    }

    public function asString(): string
    {
        return $this->value->format(DATE_ATOM);
    }

    public function isAfter(DateTimeImmutable $date): bool
    {
        return $this->value->getTimestamp() > $date->getTimestamp();
    }
}
