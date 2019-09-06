<?php

declare(strict_types=1);

namespace CinemaBot\Domain;

final class ExistingFile
{
    /** @var string */
    private $value;

    public function __construct(string $value)
    {
        if (file_exists($value) === false) {
            throw new FileMissingException(sprintf('File "%s" does not exist.', $value));
        }

        $this->value = $value;
    }

    public static function from(string $value): ExistingFile
    {
        return new self($value);
    }

    public function asString(): string
    {
        return $this->value;
    }
}
