<?php

declare(strict_types=1);

namespace CinemaBot\Application\Command;

use CinemaBot\Application\CQRS\Events;
use CinemaBot\Domain\SendNotifications\NotifierSystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendNotificationCLICommand extends Command
{
    private NotifierSystem $notifierSystem;

    public function __construct(NotifierSystem $notifierSystem)
    {
        parent::__construct();
        $this->notifierSystem = $notifierSystem;
    }

    protected function configure(): void
    {
        $this->setName('send-notifications');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        // TODO: Aus store mit pointer laden
        $events = Events::from([]);

        $this->notifierSystem->handle($events);

        return 0;
    }
}
