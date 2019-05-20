<?php

declare(strict_types=1);

namespace CinemaBot\Domain\Command;

use CinemaBot\Application\CQRS\Command;
use CinemaBot\Domain\Watchlist\Term;

class AddToWatchlistCommand implements Command
{
    /** @var Term */
    private $term;

    public function __construct(Term $term)
    {
        $this->term = $term;
    }

    public function getTerm(): Term
    {
        return $this->term;
    }
}
