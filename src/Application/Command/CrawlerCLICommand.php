<?php

declare(strict_types=1);

namespace CinemaBot\Application\Command;

use CinemaBot\Application\CQRS\CommandBus;
use CinemaBot\Domain\CinemaList\CinemaListProjection;
use CinemaBot\Domain\CrawlCinema\CrawlCinemaCommand;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CrawlerCLICommand extends Command
{
    private CommandBus $commandBus;
    private CinemaListProjection $projection;
    private LoggerInterface $logger;

    public function __construct(CommandBus $commandBus, CinemaListProjection $projection, LoggerInterface $logger)
    {
        parent::__construct();
        $this->commandBus = $commandBus;
        $this->projection = $projection;
        $this->logger = $logger;
    }

    protected function configure(): void
    {
        $this->setName('crawler');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $cinemaIDs = $this->projection->load();

        foreach ($cinemaIDs as $cinemaID) {
            $this->logger->info('Crawling cinema', ['id' => $cinemaID->asString()]);
            $this->commandBus->dispatch(new CrawlCinemaCommand($cinemaID));
        }

        return 0;
    }
}
