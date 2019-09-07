<?php

declare(strict_types=1);

namespace CinemaBot\Application\Command;

use CinemaBot\Application\CQRS\CommandBus;
use CinemaBot\Application\CQRS\EventDispatcher;
use CinemaBot\Domain\AddTerm\AddTermToWatchlistCommand;
use CinemaBot\Domain\ChatID;
use CinemaBot\Domain\ChatIDToGroupIDMap\ChatGroupProjection;
use CinemaBot\Domain\RemoveTerm\RemoveFromWatchlistCommand;
use CinemaBot\Domain\Term;
use CinemaBot\Domain\Watchlist\WatchlistProjection;
use CinemaBot\Infrastructure\Bot;
use CinemaBot\Infrastructure\TelegramToken;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Client;
use TelegramBot\Api\Types\Message;
use TelegramBot\Api\Types\Update;

final class WebHookAction
{
    /** @var CommandBus */
    private $commandBus;

    /** @var EventDispatcher */
    private $eventDispatcher;

    /** @var WatchlistProjection */
    private $projection;

    /** @var ChatGroupProjection */
    private $chatGroupProjection;

    public function __construct(CommandBus $commandBus, EventDispatcher $eventDispatcher, WatchlistProjection $projection, ChatGroupProjection $chatGroupProjection)
    {
        $this->commandBus = $commandBus;
        $this->eventDispatcher = $eventDispatcher;
        $this->projection = $projection;
        $this->chatGroupProjection = $chatGroupProjection;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $token = TelegramToken::get();

        $bot = new Client($token);
        $bot->command('help', static function (Message $message) use ($bot) {
            echo $message->getText() . PHP_EOL;
        });
        $bot->command('show', static function (Message $message) use ($bot) {
            echo $message->getText() . PHP_EOL;
        });
        $bot->command('add', static function (Message $message) use ($bot) {
            echo $message->getText() . PHP_EOL;
        });
        $bot->command('remove', static function (Message $message) use ($bot) {
            echo $message->getText() . PHP_EOL;
        });
        $bot->on(static function (Update $update) {
            echo 'left chat' . PHP_EOL;
        }, static function (Update $update) {
            return $update->getMessage()->getLeftChatMember() !== null;
        });
        $bot->on(static function (Update $update) {
            echo 'joined chat' . PHP_EOL;
        }, static function (Update $update) {
            return $update->getMessage()->getNewChatMember() !== null;
        });
        $bot->run();

//        $text = $body['message']['text'];
//        $chatId = ChatID::from((string) $body['message']['chat']['id']);
//
//        preg_match('/\/([a-z]+)( (.*))?/', $text, $matches);
//        $command = $matches[1] ?? '';
//
//        $bot = new Bot(new BotApi($token));
//
//        switch ($command) {
//            case 'help':
//                $bot->help($chatId);
//                break;
//            case 'show':
//                $groupID = $this->chatGroupProjection->loadGroupIDByChatID($chatId);
//                $watchlist = $this->projection->loadByGroupID($groupID);
//                $bot->showWatchlist($chatId, $watchlist);
//                break;
//            case 'add':
//                $term = Term::from($matches[3] ?? '');
//                $groupID = $this->chatGroupProjection->loadGroupIDByChatID($chatId);
//                $this->commandBus->dispatch(new AddTermToWatchlistCommand($groupID, $term));
//                $this->eventDispatcher->dispatch();
//                $bot->addTermToWatchlist($chatId, $term);
//                break;
//            case 'remove':
//                $term = Term::from($matches[3] ?? '');
//                $groupID = $this->chatGroupProjection->loadGroupIDByChatID($chatId);
//                $this->commandBus->dispatch(new RemoveFromWatchlistCommand($groupID, $term));
//                $this->eventDispatcher->dispatch();
//                $bot->removeTermFromWatchlist($chatId, $term);
//                break;
//        }

        return $response;
    }
}
