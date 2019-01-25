<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Tests\Seeds;

use sonrac\SimpleSeed\RollBackSeedWithCheckExists;

/**
 * Class ScopesTableSeeder.
 */
class ScopesTableSeeder extends RollBackSeedWithCheckExists
{
    /**
     * {@inheritdoc}
     */
    protected function getTable(): string
    {
        return 'oauth2_scopes';
    }

    /**
     * {@inheritdoc}
     */
    protected function getData(): array
    {
        return [
            [
                'id' => 'default',
                'title' => 'Default scope',
                'description' => 'Default scope',
                'created_at' => \time(),
            ],
            [
                'id' => 'client',
                'title' => 'Default scope',
                'description' => 'Client scope',
                'created_at' => \time(),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getWhereForRow($data): array
    {
        return ['id' => $data['id']];
    }

    /**
     * {@inheritdoc}
     */
    protected function checkDeleted($data): array
    {
        return $this->getWhereForRow($data);
    }
}
