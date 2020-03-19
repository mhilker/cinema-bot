<?php

declare(strict_types=1);

namespace CinemaBot\Domain\AddTerm;

use CinemaBot\Application\Aggregate\AbstractEventStream;
use CinemaBot\Application\CQRS\Event;
use CinemaBot\Domain\Event\GroupFoundedEvent;
use CinemaBot\Domain\Event\TermAddedEvent;
use CinemaBot\Domain\GroupID;
use CinemaBot\Domain\Group\GroupUseCase;
use CinemaBot\Domain\Term;
use CinemaBot\Domain\Terms;

final class AddTermToWatchlistUseCase extends AbstractEventStream implements GroupUseCase
{
    private GroupID $groupID;
    private Terms $terms;

    private function applyGroupFoundedEvent(GroupFoundedEvent $event): void
    {
        $this->groupID = $event->getGroupID();
        $this->terms = Terms::from([]);
    }

    public function add(Term $term): void
    {
        if ($this->terms->notContains($term)) {
            $this->record(new TermAddedEvent($this->groupID, $term));
        }
    }

    public function applyTermAddedEvent(TermAddedEvent $event): void
    {
        $this->terms = $this->terms->with($event->getTerm());
    }

    protected function apply(Event $event): void
    {
        if ($event instanceof GroupFoundedEvent) {
            $this->applyGroupFoundedEvent($event);
        }
        if ($event instanceof TermAddedEvent) {
            $this->applyTermAddedEvent($event);
        }
    }
}
