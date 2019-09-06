<?php

declare(strict_types=1);

namespace CinemaBot\Application\Command;

use CinemaBot\Application\CQRS\CommandBus;
use CinemaBot\Domain\CinemaID;
use CinemaBot\Domain\CreateCinema\CreateCinemaCommand;
use CinemaBot\Domain\URL;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateCinemaSymfonyCommand extends Command
{
    /** @var CommandBus */
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        parent::__construct();
        $this->commandBus = $commandBus;
    }

    protected function configure(): void
    {
        $this->setName('create-cinema');
        $this->addOption('url', null, InputOption::VALUE_REQUIRED);
    }

    public function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $id = CinemaID::random();
        $url = URL::from($input->getOption('url'));

        $this->commandBus->dispatch(new CreateCinemaCommand($id, $url));

        return null;
    }
}
