<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure\Telegram\Command;

use CinemaBot\Domain\ChatIDToGroupIDMap\ChatGroupMapProjection;
use CinemaBot\Domain\Group\ChatID;
use CinemaBot\Domain\MovieList\MovieListProjection;
use TelegramBot\Api\Client;
use TelegramBot\Api\Types\Message;

final class ShowMovieListTelegramCommand implements TelegramCommand
{
    private ChatGroupMapProjection $projection;
    private MovieListProjection $movieList;

    public function __construct(ChatGroupMapProjection $projection, MovieListProjection $movieList)
    {
        $this->projection = $projection;
        $this->movieList = $movieList;
    }

    public function getName(): string
    {
        return 'movies';
    }

    public function execute(Client $bot, Message $message): void
    {
        $chatID = ChatID::fromInt($message->getChat()->getId());
        $groupID = $this->projection->loadGroupIDByChatID($chatID);
        // TODO: Movies by cinema

        $names = $this->movieList->load();
        if (count($names) > 0) {
            $response = 'All movies:' . PHP_EOL;
            foreach ($names as $name) {
                $response .=  '`' . $name->asString() . '`' . PHP_EOL;
            }
        } else {
            $response = 'The current movie list is empty.';
        }

        $bot->sendMessage($chatID->asString(), $response, 'markdown');
    }
}
