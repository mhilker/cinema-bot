<?php

declare(strict_types=1);

namespace CinemaBot\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use Doctrine\Migrations\AbstractMigration;

final class Version20190906192746 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        /** @var Table $cinemaList */
        $cinemaList = $schema->getTable('cinema_list');
        $cinemaList->addColumn('id', 'bigint', ['unsigned' => true, 'autoincrement' => true,]);
        $cinemaList->addColumn('url', 'string', ['length' => 255,]);
        $cinemaList->dropPrimaryKey();
        $cinemaList->setPrimaryKey(['id']);
    }

    public function down(Schema $schema) : void
    {
        /** @var Table $cinemaList */
        $cinemaList = $schema->getTable('cinema_list');
        $cinemaList->dropPrimaryKey();
        $cinemaList->dropColumn('id');
        $cinemaList->dropColumn('url');
        $cinemaList->setPrimaryKey(['cinema_id']);
    }
}
