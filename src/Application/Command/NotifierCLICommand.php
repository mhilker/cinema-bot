<?php

declare(strict_types=1);

namespace CinemaBot\Application\Command;

use CinemaBot\Domain\Notifications\NotifierSystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class NotifierCLICommand extends Command
{
    private NotifierSystem $notifierSystem;

    public function __construct(NotifierSystem $notifierSystem)
    {
        parent::__construct();
        $this->notifierSystem = $notifierSystem;
    }

    protected function configure(): void
    {
        $this->setName('notifier');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->notifierSystem->run();

        return 0;
    }
}
