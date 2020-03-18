<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure\Telegram\Command;

use CinemaBot\Domain\ChatID;
use CinemaBot\Domain\GroupID;
use CinemaBot\Domain\Watchlist\WatchlistProjection;
use TelegramBot\Api\Client;
use TelegramBot\Api\Types\Message;

class ShowWatchlistTelegramCommand implements TelegramCommand
{
    private WatchlistProjection $watchlistProjection;

    public function __construct(WatchlistProjection $watchlistProjection)
    {
        $this->watchlistProjection = $watchlistProjection;
    }

    public function getName(): string
    {
        return 'show';
    }

    public function execute(Client $bot, Message $message): void
    {
        $chatID = ChatID::fromInt($message->getChat()->getId());
        $groupID = GroupID::random();

        $watchlist = $this->watchlistProjection->loadByGroupID($groupID);
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
