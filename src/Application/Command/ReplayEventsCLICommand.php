<?php

declare(strict_types=1);

namespace CinemaBot\Application\Command;

use CinemaBot\Application\CQRS\EventDispatcher;
use CinemaBot\Application\CQRS\EventPublisher;
use CinemaBot\Application\EventStore\EventStore;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ReplayEventsCLICommand extends Command
{
    private EventStore $eventStore;
    private EventPublisher $eventPublisher;
    private EventDispatcher $eventDispatcher;

    public function __construct(EventStore $eventStore, EventPublisher $eventPublisher, EventDispatcher $eventDispatcher)
    {
        parent::__construct();
        $this->eventStore = $eventStore;
        $this->eventPublisher = $eventPublisher;
        $this->eventDispatcher = $eventDispatcher;
    }

    protected function configure(): void
    {
        $this->setName('replay');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $events = $this->eventStore->loadAll();
        $this->eventPublisher->publish($events);
        $this->eventDispatcher->dispatch();

        return 0;
    }
}
