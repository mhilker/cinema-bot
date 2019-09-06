<?php

declare(strict_types=1);

namespace CinemaBot\Application\Command;

use CinemaBot\Application\CQRS\Events;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendNotificationCLICommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('show-found-notifications');
    }

    public function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $events = Events::from([]);

        foreach ($events as $event) {

        }
    }
}
