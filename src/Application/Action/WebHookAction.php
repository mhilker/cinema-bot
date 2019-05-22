<?php

declare(strict_types=1);

namespace CinemaBot\Application\Action;

use CinemaBot\Application\CQRS\CommandBus;
use CinemaBot\Domain\Bot;
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
        $token = file_get_contents(getenv('TELEGRAM_TOKEN_FILE'));

        $body = $request->getParsedBody();
        $text = $body['message']['text'];
        $chatId = $body['message']['chat']['id'];

        if (strpos($text, '/') !== 0) {
            return $response;
        }

        preg_match('/\/([a-z]+)( (.*))?/', $text, $matches);
        $command = $matches[1] ?? '';
        $term = Term::from($matches[3] ?? '');

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
                $this->commandBus->dispatch(new AddToWatchlistCommand($term));
                $bot->add($chatId, $term);
                break;
            case 'remove':
                $this->commandBus->dispatch(new RemoveFromWatchlistCommand($term));
                $bot->remove($chatId, $term);
                break;
        }

        $response->getBody()->write(json_encode([
            'command' => $command,
            'params'  => $term->asString(),
        ]));

        return $response;
    }
}
