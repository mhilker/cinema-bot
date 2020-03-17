<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure\Telegram;

use CinemaBot\Domain\ChatID;
use CinemaBot\Domain\GroupID;
use CinemaBot\Domain\Watchlist\WatchlistProjection;
use TelegramBot\Api\Client;
use TelegramBot\Api\Types\Update;

class ShowWatchlistTelegramHandler implements TelegramHandler
{
    private WatchlistProjection $watchlistProjection;

    public function __construct(WatchlistProjection $watchlistProjection)
    {
        $this->watchlistProjection = $watchlistProjection;
    }

    public function handle(Client $bot, Update $update, GroupID $groupID): void
    {
        $chatID = ChatID::from($update->getMessage()->getChat()->getId());

        $watchlist = $this->watchlistProjection->loadByGroupID($groupID);
        if (count($watchlist) > 0) {
            $response = 'Current watchlist:' . PHP_EOL;
            foreach ($watchlist as $term) {
                $response .=  '`' . $term->asString() . '`' . PHP_EOL;
            }
        } else {
            $response = 'The current watchlist is empty.';
        }

        $bot->sendMessage($chatID->asString(), $response, self::PARSE_MODE);
    }

    public function check(Update $update): bool
    {
        return $update->getMessage()->getText() === '/show';
    }
}
