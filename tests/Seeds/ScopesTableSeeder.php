<?php

namespace sonrac\Auth\Tests\Seeds;

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
        return 'scopes';
    }

    /**
     * {@inheritdoc}
     */
    protected function getData(): array
    {
        return [
            [
                'scope'       => 'default',
                'title'       => 'Default scope',
                'description' => 'Default scope',
                'created_at'  => \time(),
            ],
            [
                'scope'       => 'client',
                'title'       => 'Default scope',
                'description' => 'Client scope',
                'created_at'  => \time(),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getWhereForRow($data): array
    {
        return ['scope' => $data['scope']];
    }

    /**
     * {@inheritdoc}
     */
    protected function checkDeleted($data): array
    {
        return $this->getWhereForRow($data);
    }
}
