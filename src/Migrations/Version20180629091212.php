<?php

declare(strict_types=1);

namespace sonrac\Auth\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180629091212 extends AbstractMigration
{
    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema): void
    {
        $authCodes = $schema->createTable('auth_codes');
        $authCodes->addColumn('code', Type::STRING)
            ->setLength(2000)
            ->setNotnull(true);
        $authCodes->addColumn('redirect_uris', Type::TEXT)
            ->setNotnull(true);
        $authCodes->addColumn('is_revoked', Type::BOOLEAN)
            ->setNotnull(true)
            ->setDefault(false);
        $authCodes->addColumn('scopes', Type::TEXT)
            ->setNotnull(true);
        $authCodes->addColumn('user_id', Type::INTEGER)
            ->setNotnull(true);
        $authCodes->addColumn('client_id', Type::INTEGER)
            ->setNotnull(true);
        foreach (['expire_at', 'created_at', 'updated_at'] as $columnName) {
            $notNull = $columnName === 'updated_at';
            $authCodes->addColumn($columnName, Type::BIGINT)
                ->setLength(20)
                ->setNotnull($notNull);
        }

        $authCodes->setPrimaryKey(['code']);
    }

    /**
     * {@inheritdoc}
     */
    public function down(Schema $schema): void
    {
        $schema->dropTable('auth_codes');
    }
}
