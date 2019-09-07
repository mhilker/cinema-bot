<?php

declare(strict_types=1);

namespace CinemaBot;

use CinemaBot\Domain\ChatID;
use CinemaBot\Domain\Movie;
use CinemaBot\Domain\MovieName;
use CinemaBot\Domain\MovieTime;
use CinemaBot\Domain\MovieTimes;
use CinemaBot\Domain\SendNotifications\MarkdownNotificationFormatter;
use CinemaBot\Domain\SendNotifications\TelegramNotifier;
use DateTimeImmutable;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TelegramBot\Api\BotApi;

/**
 * @covers \CinemaBot\Domain\SendNotifications\TelegramNotifier
 */
final class NotifierTest extends TestCase
{
    public function testFormatsAndSendsMessage(): void
    {
        $movie = Movie::from(
            MovieName::from('Test'),
            MovieTimes::from([
                MovieTime::from(new DateTimeImmutable('2019-05-16T20:15+02:00')),
                MovieTime::from(new DateTimeImmutable('2019-05-16T21:15+02:00')),
                MovieTime::from(new DateTimeImmutable('2019-05-16T22:15+02:00')),
                MovieTime::from(new DateTimeImmutable('2019-05-17T21:15+02:00')),
                MovieTime::from(new DateTimeImmutable('2019-05-18T22:15+02:00')),
            ])
        );

        $chatID = ChatID::from('-12345678');

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

        /** @var BotApi | MockObject $telegram */
        $telegram = $this->createMock(BotApi::class);
        $telegram->expects($this->once())->method('sendMessage')->with($chatID->asString(), $message, 'markdown');

        $notifier = new TelegramNotifier($telegram, new MarkdownNotificationFormatter());
        $notifier->send($movie, $chatID);
    }
}
