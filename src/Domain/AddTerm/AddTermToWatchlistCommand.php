<?php

declare(strict_types=1);

namespace CinemaBot\Domain\AddTerm;

use CinemaBot\Application\CQRS\Command;
use CinemaBot\Domain\AddShowToCinema\Watchlist\Term;

final class AddTermToWatchlistCommand implements Command
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
