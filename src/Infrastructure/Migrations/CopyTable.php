<?php

declare(strict_types=1);

namespace CinemaBot\Infrastructure\Migrations;

use Doctrine\DBAL\Schema\Table;

trait CopyTable
{
    private function copyTable(Table $oldTable, Table $newTable): void
    {
        foreach ($oldTable->getColumns() as $column) {
            $newTable->addColumn(
                $column->getName(),
                $column->getType()->getName(),
                $column->toArray(),
            );
        }

        $newTable->setPrimaryKey($oldTable->getPrimaryKeyColumns());

        foreach ($oldTable->getIndexes() as $index) {
            if ($index->isPrimary()) {
                continue;
            }
            $newTable->addIndex(
                $index->getColumns(),
                null,
                $index->getFlags(),
                $index->getOptions(),
            );
        }
    }
}
