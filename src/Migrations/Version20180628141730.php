<?php

declare(strict_types=1);

namespace sonrac\Auth\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180628141730 extends AbstractMigration
{
    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema): void
    {
        $accessToken = $schema->createTable('clients');
        $accessToken->addColumn('name', Type::STRING)
            ->setLength(255)
            ->setNotnull(true);
        $accessToken->addColumn('token_scopes', Type::TEXT)
            ->setNotnull(true)
            ->setDefault('default');
        $accessToken->addColumn('user_id', Type::INTEGER)
            ->setNotnull(false);
        $accessToken->addColumn('client_id', Type::INTEGER)
            ->setNotnull(true);

        foreach (['expire_at', 'created_at', 'updated_at'] as $columnName) {
            $notNull = $columnName === 'updated_at';
            $accessToken->addColumn($columnName, Type::BIGINT)
                ->setLength(20)
                ->setNotnull($notNull);
        }

        $accessToken->setPrimaryKey(['name']);
    }

    /**
     * {@inheritdoc}
     */
    public function down(Schema $schema): void
    {
        $schema->dropTable('clients');
    }
}
