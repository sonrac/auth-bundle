<?php

declare(strict_types=1);

namespace sonrac\Auth\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180629091609 extends AbstractMigration
{
    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema): void
    {
        $scopes = $schema->createTable('scopes');
        $scopes->addColumn('scope', Type::STRING)
            ->setNotnull(true);
        $scopes->addColumn('title', Type::TEXT)
            ->setLength(255)
            ->setNotnull(true);
        $scopes->addColumn('description', Type::TEXT)
            ->setLength(2000)
            ->setNotnull(true);
        $scopes->addColumn('permissions', Type::TEXT)
            ->setNotnull(false)
            ->setDefault('');

        foreach (['created_at', 'updated_at'] as $columnName) {
            $notNull = $columnName !== 'updated_at';
            $scopes->addColumn($columnName, Type::BIGINT)
                ->setLength(20)
                ->setNotnull($notNull);
        }

        $scopes->setPrimaryKey(['scope']);
    }

    /**
     * {@inheritdoc}
     */
    public function down(Schema $schema): void
    {
        $schema->dropTable('scopes');
    }
}
