<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

final class Version20200321130618 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Rename "movie_time" to "show_time".';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->getTable('show_list');
        $table->dropColumn('movie_time');
        $table->addColumn('show_time', Types::DATETIME_IMMUTABLE);
    }

    public function down(Schema $schema): void
    {
        $table = $schema->getTable('show_list');
        $table->dropColumn('show_time');
        $table->addColumn('movie_time', Types::DATETIME_IMMUTABLE);
    }
}
