<?php

declare(strict_types=1);

namespace sonrac\Auth\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;
use Doctrine\Migrations\AbstractMigration;
use Sonrac\OAuth2\Adapter\League\Grant\AuthCodeGrant;
use Sonrac\OAuth2\Adapter\League\Grant\ClientCredentialsGrant;
use Sonrac\OAuth2\Adapter\League\Grant\ImplicitGrant;
use Sonrac\OAuth2\Adapter\League\Grant\PasswordGrant;
use Sonrac\OAuth2\Adapter\League\Grant\RefreshTokenGrant;

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
        $clients = $schema->createTable('clients');
        $clients->addColumn('name', Type::STRING)
            ->setLength(255)
            ->setNotnull(true);
        $clients->addColumn('description', Type::STRING)
            ->setLength(2000)
            ->setNotnull(true)
            ->setDefault('');
        $clients->addColumn('secret', Type::STRING)
            ->setLength(2000)
            ->setNotnull(true);
        $clients->addColumn('redirect_uris', Type::TEXT)
            ->setNotnull(false);

        $clients->addColumn('allowed_grant_types', Type::TEXT)
            ->setNotnull(true)
            ->setDefault(\implode('|', [
                AuthCodeGrant::TYPE,
                ClientCredentialsGrant::TYPE,
                ImplicitGrant::TYPE,
                PasswordGrant::TYPE,
                RefreshTokenGrant::TYPE,
            ]));

        $clients->addColumn('user_id', Type::INTEGER)
            ->setNotnull(false);

        foreach (['expire_at', 'created_at', 'updated_at'] as $columnName) {
            $notNull = $columnName === 'created_at';
            $clients->addColumn($columnName, Type::BIGINT)
                ->setLength(20)
                ->setNotnull($notNull);
        }

        $clients->setPrimaryKey(['name']);
    }

    /**
     * {@inheritdoc}
     */
    public function down(Schema $schema): void
    {
        $schema->dropTable('clients');
    }
}
