<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use Doctrine\Migrations\AbstractMigration;

final class Version20190906192746 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Remove primary key from table "cinema_list".';
    }

    public function up(Schema $schema): void
    {
        /** @var Table $cinemaList */
        $cinemaList = $schema->getTable('cinema_list');
        $cinemaList->dropPrimaryKey();
    }

    public function down(Schema $schema): void
    {
        /** @var Table $cinemaList */
        $cinemaList = $schema->getTable('cinema_list');
        $cinemaList->setPrimaryKey(['cinema_id']);
    }
}
