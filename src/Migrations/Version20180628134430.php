<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180628134430 extends AbstractMigration
{
    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema): void
    {
        $accessToken = $schema->createTable('access_tokens');
        $accessToken->addColumn('token', Type::STRING)
            ->setLength(2000)
            ->setNotnull(true);
        $accessToken->addColumn('is_revoked', Type::BOOLEAN)
            ->setNotnull(true)
            ->setDefault(false);
        $accessToken->addColumn('token_scopes', Type::TEXT)
            ->setNotnull(true)
            ->setDefault('default');

        $accessToken->addColumn('grant_type', Type::STRING)
            ->setNotnull(true)
            ->setLength(50);

        $accessToken->addColumn('user_id', Type::INTEGER)
            ->setNotnull(false);
        $accessToken->addColumn('client_id', Type::INTEGER)
            ->setNotnull(true);

        foreach (['expire_at', 'created_at', 'updated_at'] as $columnName) {
            $notNull = $columnName !== 'updated_at';
            $accessToken->addColumn($columnName, Type::BIGINT)
                ->setLength(20)
                ->setNotnull($notNull);
        }

        $accessToken->setPrimaryKey(['token']);
    }

    /**
     * {@inheritdoc}
     */
    public function down(Schema $schema): void
    {
        $schema->dropTable('access_tokens');
    }
}
