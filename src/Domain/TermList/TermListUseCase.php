<?php

declare(strict_types=1);

namespace CinemaBot\Domain\TermList;

use CinemaBot\Application\CQRS\Event;
use CinemaBot\Application\EventStream\AbstractEventStream;
use CinemaBot\Domain\Event\GroupFoundedEvent;
use CinemaBot\Domain\Event\TermAddedEvent;
use CinemaBot\Domain\Event\TermRemovedEvent;
use CinemaBot\Domain\Group\GroupUseCase;
use CinemaBot\Domain\Group\GroupID;
use CinemaBot\Domain\Term;
use CinemaBot\Domain\Terms;

final class TermListUseCase extends AbstractEventStream implements GroupUseCase
{
    private GroupID $id;
    private Terms $terms;

    private function applyGroupFoundedEvent(GroupFoundedEvent $event): void
    {
        $this->id = $event->getGroupID();
        $this->terms = Terms::from([]);
    }

    public function add(Term $term): void
    {
        if ($this->terms->notContains($term)) {
            $this->record(new TermAddedEvent($this->id, $term));
        }
    }

    public function remove(Term $term): void
    {
        if ($this->terms->contains($term)) {
            $this->record(new TermRemovedEvent($this->id, $term));
        }
    }

    private function applyTermAddedEvent(TermAddedEvent $event): void
    {
        $this->terms = $this->terms->with($event->getTerm());
    }

    private function applyTermRemovedEvent(TermRemovedEvent $event): void
    {
        $this->terms = $this->terms->without($event->getTerm());
    }

    protected function apply(Event $event): void
    {
        if ($event instanceof GroupFoundedEvent) {
            $this->applyGroupFoundedEvent($event);
        }
        if ($event instanceof TermAddedEvent) {
            $this->applyTermAddedEvent($event);
        }
        if ($event instanceof TermRemovedEvent) {
            $this->applyTermRemovedEvent($event);
        }
    }
}
