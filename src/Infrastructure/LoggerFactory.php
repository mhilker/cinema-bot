<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure;

use DateTimeZone;
use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\MemoryPeakUsageProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Processor\PsrLogMessageProcessor;
use Monolog\Processor\WebProcessor;
use Psr\Log\LoggerInterface;

final class LoggerFactory
{
    public function __invoke(): LoggerInterface
    {
        $stream = new StreamHandler('php://stdout');
        $stream->setFormatter(new JsonFormatter());
        $stream->pushProcessor(new PsrLogMessageProcessor());
        $stream->pushProcessor(new WebProcessor());
        $stream->pushProcessor(new MemoryUsageProcessor());
        $stream->pushProcessor(new MemoryPeakUsageProcessor());

        return new Logger('cinema-bot', [$stream], [], new DateTimeZone('UTC'));
    }
}
