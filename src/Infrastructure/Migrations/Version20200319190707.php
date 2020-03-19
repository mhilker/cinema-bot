<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200319190707 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adds unique index to "watchlist" table.';
    }

    public function up(Schema $schema): void
    {
        $watchlist = $schema->getTable('watchlist');
        $watchlist->addUniqueIndex(['group_id', 'term'], 'uq_group_id_term');
    }

    public function down(Schema $schema): void
    {
        $watchlist = $schema->getTable('watchlist');
        $watchlist->dropIndex('uq_group_id_term');
    }
}
