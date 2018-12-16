<?php declare(strict_types=1);

namespace Sonrac\OAuth2\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181206204420 extends AbstractMigration
{
    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema): void
    {
        $table = $schema->getTable('clients');

        $table->dropPrimaryKey();

        $table->dropColumn('user_id');

        $table->getColumn('description')
            ->setType(Type::getType(Type::TEXT));

        $table->getColumn('secret')
            ->setLength(255);

        $table->dropColumn('expire_at');

        $table->getColumn('allowed_grant_types')
            ->setDefault('["authorization_code","client_credentials","implicit","password","refresh_token"]');

        $table->addColumn('id', Type::STRING)
            ->setLength(255)
            ->setNotnull(true);

        $table->setPrimaryKey(['id']);
    }

    /**
     * {@inheritdoc}
     */
    public function down(Schema $schema): void
    {
        $table = $schema->getTable('clients');

        $table->dropPrimaryKey();
        $table->dropColumn('id');

        $table->getColumn('allowed_grant_types')
            ->setDefault('implicit|authorization_code|password|client_credentials');

        $table->addColumn('expire_at', Type::BIGINT)
            ->setNotnull(false);

        $table->getColumn('secret')
            ->setLength(2000);

        $table->getColumn('description')
            ->setType(Type::getType(Type::STRING))
            ->setLength(2000);

        $table->addColumn('user_id', Type::INTEGER)
            ->setNotnull(false);

        $table->setPrimaryKey(['name']);
    }
}
