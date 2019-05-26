<?php

declare(strict_types=1);

namespace CinemaBot\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20190521184307 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the table "watchlist"';
    }

    public function up(Schema $schema): void
    {
        $watchlist = $schema->createTable('watchlist');
        $watchlist->addColumn('term', 'string', [
            'length' => 256,
        ]);
    }
    public function down(Schema $schema): void
    {
        $schema->dropTable('watchlist');
    }
}
