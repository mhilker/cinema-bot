<?php

declare(strict_types=1);

namespace CinemaBot\Application\Action;

use CinemaBot\Application\CQRS\CommandBus;
use CinemaBot\Application\CQRS\EventBus;
use CinemaBot\Infrastructure\Bot;
use CinemaBot\Domain\ChatID;
use CinemaBot\Domain\AddTerm\AddTermToWatchlistCommand;
use CinemaBot\Domain\RemoveTerm\RemoveFromWatchlistCommand;
use CinemaBot\Domain\AddShowToCinema\Watchlist\Term;
use CinemaBot\Domain\AddShowToCinema\Watchlist\WatchlistProjection;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TelegramBot\Api\BotApi;

class WebHookAction
{
    /** @var CommandBus */
    private $commandBus;

    /** @var EventBus */
    private $eventBus;

    /** @var WatchlistProjection */
    private $projection;

    public function __construct(CommandBus $commandBus, EventBus $eventBus, WatchlistProjection $projection)
    {
        $this->commandBus = $commandBus;
        $this->eventBus = $eventBus;
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

        if (strpos($text, '/') !== 0) {
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
                $bot->showWatchlist($chatId, $watchlist);
                break;
            case 'add':
                $term = Term::from($matches[3] ?? '');
                $this->commandBus->dispatch(new AddTermToWatchlistCommand($term));
                $this->eventBus->dispatch();
                $bot->addTermToWatchlist($chatId, $term);
                break;
            case 'remove':
                $term = Term::from($matches[3] ?? '');
                $this->commandBus->dispatch(new RemoveFromWatchlistCommand($term));
                $this->eventBus->dispatch();
                $bot->removeTermFromWatchlist($chatId, $term);
                break;
        }

        return $response;
    }
}
