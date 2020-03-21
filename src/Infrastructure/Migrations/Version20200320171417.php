<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200320171417 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the table "movie_list".';
    }

    public function up(Schema $schema): void
    {
        $idMap = $schema->createTable('movie_list');
        $idMap->addColumn('id', 'bigint', [
            'unsigned' => true,
            'autoincrement' => true,
        ]);
        $idMap->addColumn('movie_name', 'string', [
            'length' => 255,
            'fixed' => false,
        ]);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('movie_list');
    }
}
