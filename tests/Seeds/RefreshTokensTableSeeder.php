<?php

namespace sonrac\Auth\Tests\Seeds;

use sonrac\SimpleSeed\RollBackSeedWithCheckExists;

/**
 * Class RefreshTokensTableSeeder
 */
class RefreshTokensTableSeeder extends RollBackSeedWithCheckExists
{
    /**
     * @inheritDoc
     */
    protected function getTable(): string
    {
        return 'refresh_tokens';
    }

    /**
     * @inheritDoc
     */
    protected function getData(): array
    {
        return [
            [
                'refresh_token' => 'refresh_token1',
                'token' => 'test_token',
                'expire_at' => time() + 3600,
                'created_at' => time()
            ]
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getWhereForRow($data): array
    {
        return ['refresh_token' => $data['refresh_token']];
    }

    /**
     * @inheritDoc
     */
    protected function checkDeleted($data): array
    {
        return $this->getWhereForRow($data);
    }
}
