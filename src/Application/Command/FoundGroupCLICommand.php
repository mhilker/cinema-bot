<?php

declare(strict_types=1);

namespace CinemaBot\Application\Command;

use CinemaBot\Application\CQRS\CommandBus;
use CinemaBot\Application\CQRS\EventDispatcher;
use CinemaBot\Domain\ChatID;
use CinemaBot\Domain\FoundGroup\FoundGroupCommand;
use CinemaBot\Domain\GroupID;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class FoundGroupCLICommand extends Command
{
    /** @var CommandBus */
    private $commandBus;

    /** @var EventDispatcher */
    private $eventDispatcher;

    public function __construct(CommandBus $commandBus, EventDispatcher $eventDispatcher)
    {
        parent::__construct();
        $this->commandBus = $commandBus;
        $this->eventDispatcher = $eventDispatcher;
    }

    protected function configure(): void
    {
        $this->setName('found-group');
        $this->addOption('chatID', null, InputOption::VALUE_REQUIRED);
    }

    public function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $ID = GroupID::random();
        $chatID = ChatID::from($input->getOption('chatID'));

        $this->commandBus->dispatch(new FoundGroupCommand($ID, $chatID));
        $this->eventDispatcher->dispatch();

        return null;
    }
}