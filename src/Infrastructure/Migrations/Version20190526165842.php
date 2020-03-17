<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20190526165842 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the table "cinema_list".';
    }

    public function up(Schema $schema): void
    {
        $cinemaList = $schema->createTable('cinema_list');
        $cinemaList->addColumn('cinema_id', 'string', [
            'length' => 36,
            'fixed' => true,
        ]);
        $cinemaList->setPrimaryKey(['cinema_id']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('cinema_list');
    }
}
