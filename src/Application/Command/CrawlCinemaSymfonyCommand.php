<?php

declare(strict_types=1);

namespace CinemaBot\Application\Command;

use CinemaBot\Application\CQRS\CommandBus;
use CinemaBot\Application\CQRS\EventBus;
use CinemaBot\Domain\AddShowToCinema\CinemaList\CinemaListProjection;
use CinemaBot\Domain\AddShowToCinema\CrawlCinemaCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CrawlCinemaSymfonyCommand extends Command
{
    /** @var CommandBus */
    private $commandBus;

    /** @var EventBus */
    private $eventBus;

    /** @var CinemaListProjection */
    private $projection;

    public function __construct(CommandBus $commandBus, EventBus $eventBus, CinemaListProjection $projection)
    {
        parent::__construct();
        $this->commandBus = $commandBus;
        $this->eventBus = $eventBus;
        $this->projection = $projection;
    }

    protected function configure(): void
    {
        $this->setName('crawl-cinema');
    }

    public function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $cinemaIDs = $this->projection->load();

        foreach ($cinemaIDs as $cinemaID) {
            $this->commandBus->dispatch(new CrawlCinemaCommand($cinemaID));
            $this->eventBus->dispatch();
        }

        return null;
    }
}
