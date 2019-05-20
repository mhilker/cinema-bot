<?php

declare(strict_types=1);

namespace CinemaBot\Application\Action;

use CinemaBot\Application\CQRS\CommandBus;
use CinemaBot\Domain\Bot;
use CinemaBot\Domain\Command\AddToWatchlistCommand;
use CinemaBot\Domain\Command\RemoveFromWatchlistCommand;
use CinemaBot\Domain\Watchlist\Term;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TelegramBot\Api\BotApi;

class WebHookAction
{
    /** @var CommandBus */
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $token = getenv('TELEGRAM_TOKEN');

        $body = $request->getParsedBody();
        $text = $body['message']['text'];
        $chatId = $body['message']['chat']['id'];

        if (strpos($text, '/') !== 0) {
            return $response;
        }

        preg_match('/\/([a-z]+)( (.*))?/', $text, $matches);
        $command = $matches[1] ?? '';
        $params = Term::from($matches[2] ?? '');

        $bot = new Bot(new BotApi($token));

        switch ($command) {
            case 'help':
                $bot->help($chatId);
                break;
            case 'show':
                $bot->show($chatId);
                break;
            case 'add':
                $this->commandBus->dispatch(new AddToWatchlistCommand($params));
                $bot->add($chatId, $params->asString());
                break;
            case 'remove':
                $this->commandBus->dispatch(new RemoveFromWatchlistCommand($params));
                $bot->remove($chatId, $params->asString());
                break;
        }

        $response->getBody()->write(json_encode([
            'command' => $command,
            'params'  => $params,
        ]));

        return $response;
    }
}
