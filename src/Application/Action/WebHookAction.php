<?php

declare(strict_types=1);

namespace CinemaBot\Application\Action;

use CinemaBot\Application\CQRS\CommandBus;
use CinemaBot\Domain\Bot;
use CinemaBot\Domain\ChatID;
use CinemaBot\Domain\Command\AddToWatchlistCommand;
use CinemaBot\Domain\Command\RemoveFromWatchlistCommand;
use CinemaBot\Domain\Watchlist\Term;
use CinemaBot\Domain\Watchlist\WatchlistProjection;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TelegramBot\Api\BotApi;

class WebHookAction
{
    /** @var CommandBus */
    private $commandBus;

    /** @var WatchlistProjection */
    private $projection;

    public function __construct(CommandBus $commandBus, WatchlistProjection $projection)
    {
        $this->commandBus = $commandBus;
        $this->projection = $projection;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $file = getenv('TELEGRAM_TOKEN_FILE');

        if ($file === false) {
            $token = getenv('TELEGRAM_TOKEN');
        } else {
            $token = file_get_contents($file);
        }

        $body = $request->getParsedBody();
        $text = $body['message']['text'];
        $chatId = ChatID::from((string) $body['message']['chat']['id']);

        if (strpos($text, '/') != 0) {
            return $response;
        }

        preg_match('/\/([a-z]+)( (.*))?/', $text, $matches);
        $command = $matches[1] ?? '';

        $bot = new Bot(new BotApi($token));

        switch ($command) {
            case 'help':
                $bot->help($chatId);
                break;
            case 'show':
                $watchlist = $this->projection->getAll();
                $bot->show($chatId, $watchlist);
                break;
            case 'add':
                $term = Term::from($matches[3] ?? '');
                $this->commandBus->dispatch(new AddToWatchlistCommand($term));
                $bot->add($chatId, $term);
                break;
            case 'remove':
                $term = Term::from($matches[3] ?? '');
                $this->commandBus->dispatch(new RemoveFromWatchlistCommand($term));
                $bot->remove($chatId, $term);
                break;
        }

        return $response;
    }
}
