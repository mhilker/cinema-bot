<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200322120706 extends AbstractMigration
{
    use CopyTable;

    public function getDescription(): string
    {
        return 'Rename "watchlist" to "watch_list".';
    }

    public function up(Schema $schema): void
    {
        $oldTable = $schema->getTable('watchlist');
        $newTable = $schema->createTable('watch_list');
        $this->copyTable($oldTable, $newTable);

        $schema->dropTable('watchlist');
    }

    public function down(Schema $schema): void
    {
        $oldTable = $schema->getTable('watch_list');
        $newTable = $schema->createTable('watchlist');
        $this->copyTable($oldTable, $newTable);

        $schema->dropTable('watch_list');
    }
}
