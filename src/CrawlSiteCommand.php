<?php

declare(strict_types=1);

namespace CinemaBot;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TelegramBot\Api\BotApi;

class CrawlSiteCommand extends Command
{
    protected function configure(): void
    {
        $this->setName('crawl-site');
    }

    public function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $downloader = new Downloader();
        $fileName = $downloader->download('https://www.cinemotion-kino.de/hameln/kinoprogramm');

        $parser = new Parser();
        $movie = $parser->parse($fileName, 'John Wick: Kapitel 3');

        $token = getenv('TELEGRAM_TOKEN');
        $chatId = getenv('TELEGRAM_CHAT_ID');

        $telegram = new BotApi($token);
        $notifier = new Notifier($telegram);
        $notifier->send($movie, $chatId);

        return null;
    }
}
