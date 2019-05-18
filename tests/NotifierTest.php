<?php

declare(strict_types=1);

namespace CinemaBot;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use TelegramBot\Api\BotApi;

/**
 * @covers \CinemaBot\Notifier
 */
class NotifierTest extends TestCase
{
    public function testFormatsAndSendsMessage(): void
    {
        $movie = new Movie('Test', new MovieTimes([
            new MovieTime(new DateTimeImmutable('2019-05-16T20:15+02:00')),
            new MovieTime(new DateTimeImmutable('2019-05-16T21:15+02:00')),
            new MovieTime(new DateTimeImmutable('2019-05-16T22:15+02:00')),
            new MovieTime(new DateTimeImmutable('2019-05-17T21:15+02:00')),
            new MovieTime(new DateTimeImmutable('2019-05-18T22:15+02:00')),
        ]));

        $chatId = '-12345678';

        $message = <<<MESSAGE
        Test
        *Donnerstag (16.05.2019)*:
        `20:15`
        `21:15`
        `22:15`
        *Freitag (17.05.2019)*:
        `21:15`
        *Samstag (18.05.2019)*:
        `22:15`
        
        MESSAGE;

        $telegram = $this->createMock(BotApi::class);
        $telegram->expects($this->once())->method('sendMessage')->with($chatId, $message, 'markdown', true);

        $notifier = new Notifier($telegram);
        $notifier->send($movie, $chatId);
    }
}
