<?php

declare(strict_types=1);

namespace CinemaBot\Application\Command;

use CinemaBot\Infrastructure\CopyDownloader;
use CinemaBot\Infrastructure\DOMParser;
use CinemaBot\Infrastructure\TelegramNotifier;
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
        $token = getenv('TELEGRAM_TOKEN');
        $chatId = getenv('TELEGRAM_CHAT_ID');

        $downloader = new CopyDownloader();
        $fileName = $downloader->download('https://www.cinemotion-kino.de/hameln/kinoprogramm');

        $parser = new DOMParser();
        $movies = $parser->parse($fileName);

        $movie = $movies->getByName('John Wick: Kapitel 3');
        if (!$movie) {
            return null;
        }

        $telegram = new BotApi($token);
        $notifier = new TelegramNotifier($telegram);
        $notifier->send($movie, $chatId);

        return null;
    }
}
