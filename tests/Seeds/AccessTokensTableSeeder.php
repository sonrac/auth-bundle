<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Tests\Seeds;

use sonrac\SimpleSeed\RollBackSeedWithCheckExists;

/**
 * Class AccessTokensTableSeeder.
 */
class AccessTokensTableSeeder extends RollBackSeedWithCheckExists
{
    /**
     * {@inheritdoc}
     */
    protected function getTable(): string
    {
        return 'oauth2_access_tokens';
    }

    /**
     * {@inheritdoc}
     */
    protected function getData()
    {
        return [
            [
                'id' => 'test_token',
                'client_id' => 'test_client',
                'scopes' => \json_encode(['default']),
                'expire_at' => \time() - 3600,
                'is_revoked' => 0,
                'created_at' => \time(),
            ],
            [
                'id' => 'test_token1',
                'client_id' => 'test_client',
                'scopes' => \json_encode(['default']),
                'expire_at' => \time() + 3600,
                'is_revoked' => 0,
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
