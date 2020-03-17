<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure;

use CinemaBot\Infrastructure\Doctrine\DoctrineConnectionFactory;
use DI\Definition\Source\Autowiring;
use DI\Definition\Source\DefinitionArray;
use Doctrine\DBAL\Driver\Connection;
use function DI\factory;

final class CinemaBotConfig extends DefinitionArray
{
    public function __construct()
    {
        parent::__construct([
            'db' => require __DIR__ . '/../../doctrine.php',

            Connection::class       => factory(DoctrineConnectionFactory::class),
        ]);
    }
}
