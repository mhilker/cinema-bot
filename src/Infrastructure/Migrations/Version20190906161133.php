<?php

declare(strict_types=1);

namespace CinemaBot\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use Doctrine\Migrations\AbstractMigration;

final class Version20190906161133 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add columns "id" and "group_id" to table "watchlist"';
    }

    public function up(Schema $schema) : void
    {
        /** @var Table $watchlist */
        $watchlist = $schema->getTable('watchlist');
        $watchlist->addColumn('id', 'bigint', ['unsigned' => true, 'autoincrement' => true,]);
        $watchlist->addColumn('group_id', 'string', ['length' => 36, 'fixed' => true,]);
        $watchlist->setPrimaryKey(['id']);
    }

    public function down(Schema $schema) : void
    {
        /** @var Table $watchlist */
        $watchlist = $schema->getTable('watchlist');
        $watchlist->dropPrimaryKey();
        $watchlist->dropColumn('id');
        $watchlist->dropColumn('group_id');
    }
}
