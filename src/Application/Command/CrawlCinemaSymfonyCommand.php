<?php

declare(strict_types=1);

namespace CinemaBot\Application\Command;

use CinemaBot\Application\CQRS\CommandBus;
use CinemaBot\Domain\CinemaList\CinemaListProjection;
use CinemaBot\Domain\AddShowToCinema\CrawlCinemaCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CrawlCinemaSymfonyCommand extends Command
{
    /** @var CommandBus */
    private $commandBus;

    /** @var CinemaListProjection */
    private $projection;

    public function __construct(CommandBus $commandBus, CinemaListProjection $projection)
    {
        parent::__construct();
        $this->commandBus = $commandBus;
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
        }

        return null;
    }
}
