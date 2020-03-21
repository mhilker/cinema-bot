<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

final class Version20190906161133 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add columns "id" and "group_id" to table "watchlist".';
    }

    public function up(Schema $schema): void
    {
        $watchlist = $schema->getTable('watchlist');
        $watchlist->addColumn('id', TYPES::BIGINT, [
            'unsigned' => true,
            'autoincrement' => true,
        ]);
        $watchlist->addColumn('group_id', TYPES::STRING, [
            'length' => 36,
            'fixed' => true,
        ]);
        $watchlist->setPrimaryKey(['id']);
    }

    public function down(Schema $schema): void
    {
        $watchlist = $schema->getTable('watchlist');
        $watchlist->dropPrimaryKey();
        $watchlist->dropColumn('id');
        $watchlist->dropColumn('group_id');
    }
}
