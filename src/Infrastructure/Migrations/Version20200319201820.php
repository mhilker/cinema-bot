<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200319201820 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the table "show_list".';
    }

    public function up(Schema $schema): void
    {
        $idMap = $schema->createTable('show_list');
        $idMap->addColumn('id', 'bigint', [
            'unsigned' => true,
            'autoincrement' => true,
        ]);
        $idMap->addColumn('cinema_id', 'string', [
            'length' => 36,
            'fixed' => true,
        ]);
        $idMap->addColumn('movie_name', 'string', [
            'length' => 255,
            'fixed' => false,
        ]);
        $idMap->addColumn('movie_time', 'datetime');
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('show_list');
    }
}
