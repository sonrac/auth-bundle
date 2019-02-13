<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Tests\Seeds;

use sonrac\SimpleSeed\RollBackSeedWithCheckExists;

/**
 * Class AuthCodesTableSeeder.
 */
class AuthCodesTableSeeder extends RollBackSeedWithCheckExists
{
    /**
     * {@inheritdoc}
     */
    protected function getTable(): string
    {
        return 'oauth2_auth_codes';
    }

    /**
     * {@inheritdoc}
     */
    protected function getData()
    {
        return [
            [
                'id' => 'test_code',
                'client_id' => 'test_client',
                'user_id' => 1,
                'redirect_uri' => 'test',
                'scopes' => \json_encode(['client', 'admin']),
                'expire_at' => \time(),
                'is_revoked' => 0,
                'created_at' => \time(),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function checkDeleted($data): array
    {
        return $this->getWhereForRow($data);
    }

    /**
     * {@inheritdoc}
     */
    protected function getWhereForRow($data): array
    {
        return ['id' => $data['id']];
    }
}
