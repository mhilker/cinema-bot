<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure\Telegram\Command;

use CinemaBot\Domain\ChatID;
use CinemaBot\Domain\ChatIDToGroupIDMap\ChatGroupProjection;
use CinemaBot\Domain\Watchlist\WatchlistProjection;
use TelegramBot\Api\Client;
use TelegramBot\Api\Types\Message;

class ShowWatchlistTelegramCommand implements TelegramCommand
{
    private ChatGroupProjection $projection;
    private WatchlistProjection $watchlist;

    public function __construct(ChatGroupProjection $projection, WatchlistProjection $watchlist)
    {
        $this->projection = $projection;
        $this->watchlist = $watchlist;
    }

    public function getName(): string
    {
        return 'show';
    }

    public function execute(Client $bot, Message $message): void
    {
        $chatID = ChatID::fromInt($message->getChat()->getId());
        $groupID = $this->projection->loadGroupIDByChatID($chatID);

        $watchlist = $this->watchlist->loadByGroupID($groupID);
        if (count($watchlist) > 0) {
            $response = 'Current watchlist:' . PHP_EOL;
            foreach ($watchlist as $term) {
                $response .=  '`' . $term->asString() . '`' . PHP_EOL;
            }
        } else {
            $response = 'The current watchlist is empty.';
        }

        $bot->sendMessage($chatID->asString(), $response, 'markdown');
    }
}
