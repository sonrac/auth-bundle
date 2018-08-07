<?php

declare(strict_types=1);

namespace sonrac\Auth\Tests\Seeds;

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
        return 'refresh_tokens';
    }

    /**
     * {@inheritdoc}
     */
    protected function getData(): array
    {
        return [
            [
                'refresh_token' => 'refresh_token1',
                'token'         => 'test_token',
                'expire_at'     => \time() + 3600,
                'created_at'    => \time(),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getWhereForRow($data): array
    {
        return ['refresh_token' => $data['refresh_token']];
    }

    /**
     * {@inheritdoc}
     */
    protected function checkDeleted($data): array
    {
        return $this->getWhereForRow($data);
    }
}
