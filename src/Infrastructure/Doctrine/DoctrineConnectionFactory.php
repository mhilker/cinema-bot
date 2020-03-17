<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Psr\Container\ContainerInterface;

final class DoctrineConnectionFactory
{
    public function __invoke(ContainerInterface $container): Connection
    {
        $config = $container->get('db');

        return DriverManager::getConnection($config);
    }
}
