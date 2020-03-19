<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20190907075325 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change length of "term" column in "watchlist" table.';
    }

    public function up(Schema $schema): void
    {
        $watchlist = $schema->getTable('watchlist');
        $watchlist->changeColumn('term', [
            'length' => 255,
        ]);
    }

    public function down(Schema $schema): void
    {
        $watchlist = $schema->getTable('watchlist');
        $watchlist->changeColumn('term', [
            'length' => 256,
        ]);
    }
}
