<?php

declare(strict_types=1);

namespace CinemaBot\Domain\AddShowToCinema\Watchlist;

interface WatchlistProjection
{
    public function getAll(): Watchlist;

    public function add(Term $value): void;

    public function remove(Term $value): void;
}
