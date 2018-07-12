<?php

namespace sonrac\Auth\Tests\Seeds;

use sonrac\Auth\Entity\Client;
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
        return 'access_tokens';
    }

    /**
     * {@inheritdoc}
     */
    protected function getData()
    {
        return [
            [
                'token'      => 'test_token',
                'grant_type' => \json_encode([Client::GRANT_CLIENT_CREDENTIALS]),
                'client_id'  => 'Test Client',
                'expire_at'  => \time() - 3600,
                'created_at' => \time(),
            ],
            [
                'token'      => 'test_token1',
                'grant_type' => \json_encode([Client::GRANT_CLIENT_CREDENTIALS]),
                'client_id'  => 'Test Client',
                'expire_at'  => \time() + 3600,
                'created_at' => \time(),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getWhereForRow($data): array
    {
        return ['token' => $data['token']];
    }

    /**
     * {@inheritdoc}
     */
    protected function checkDeleted($data): array
    {
        return $this->getWhereForRow($data);
    }
}
