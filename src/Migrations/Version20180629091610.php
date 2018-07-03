<?php

declare(strict_types=1);

namespace sonrac\Auth\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180629091610 extends AbstractMigration
{
    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema): void
    {
        $refreshToken = $schema->createTable('refresh_tokens');

        $refreshToken->addColumn('refresh_token', Type::STRING)
            ->setLength(2000)
            ->setNotnull(true);
        $refreshToken->addColumn('token', Type::STRING)
            ->setLength(2000)
            ->setNotnull(true);
        $refreshToken->addColumn('token_scopes', Type::TEXT)
            ->setNotnull(true)
            ->setDefault('default');
        $refreshToken->addColumn('is_revoked', Type::BOOLEAN)
            ->setNotnull(true)
            ->setDefault(false);

        foreach (['expire_at', 'created_at', 'updated_at'] as $columnName) {
            $notNull = $columnName === 'updated_at';
            $refreshToken->addColumn($columnName, Type::BIGINT)
                ->setLength(20)
                ->setNotnull($notNull);
        }

        $refreshToken->setPrimaryKey(['refresh_token']);
    }

    /**
     * {@inheritdoc}
     */
    public function down(Schema $schema): void
    {
        $schema->dropTable('refresh_tokens');
    }
}
