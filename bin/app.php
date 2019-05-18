<?php

declare(strict_types=1);

use CinemaBot\CrawlSiteCommand;
use Symfony\Component\Console\Application;

require __DIR__ . '/../vendor/autoload.php';

$app = new Application();
$app->add(new CrawlSiteCommand());
$app->run();
