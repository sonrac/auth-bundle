<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Tests\Seeds;

use sonrac\SimpleSeed\RollBackSeedWithCheckExists;

/**
 * Class RefreshTokensTableSeeder.
 */
class RefreshTokensTableSeeder extends RollBackSeedWithCheckExists
{
    /**
     * {@inheritdoc}
     */
    protected function getTable(): string
    {
        return 'oauth2_refresh_tokens';
    }

    /**
     * {@inheritdoc}
     */
    protected function getData(): array
    {
        return [
            [
                'id'           => 'refresh_token1',
                'access_token' => 'test_token',
                'expire_at'    => \time() + 3600,
                'is_revoked'   => 0,
                'created_at'   => \time(),
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
